<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'asset_type',
        'purchase_date',
        'disposal_date',
        'location_id',
        'department',
        'responsible_person',
        'status',
        'qr_token',
        'latest_inspection_date',
        'image_path',
        'images',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'disposal_date' => 'date',
        'latest_inspection_date' => 'date',
        'images' => 'array',
    ];

    // Accessor for backward compatibility - returns first image
    public function getImagePathAttribute($value)
    {
        if ($value) {
            return $value;
        }
        $images = $this->images ?? [];
        return $images[0] ?? null;
    }

    // Get all images array
    public function getAllImagesAttribute(): array
    {
        return $this->images ?? [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (empty($asset->qr_token)) {
                $asset->qr_token = bin2hex(random_bytes(16));
            }
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class)->withDefault([
            'building' => 'Unknown',
            'floor' => '',
            'room' => '',
        ]);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class)->orderBy('inspected_at', 'desc');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class)->orderBy('transferred_at', 'desc');
    }

    public function latestInspection()
    {
        return $this->hasOne(Inspection::class)->latestOfMany('inspected_at');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class)->orderBy('requested_at', 'desc');
    }

    public function pendingRepairs()
    {
        return $this->hasMany(Repair::class)->where('status', 'pending');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class)->orderBy('borrowed_at', 'desc');
    }

    public function latestLoan()
    {
        return $this->hasOne(Loan::class)->latestOfMany('borrowed_at');
    }

    public function getPublicQrUrlAttribute(): string
    {
        // 1. Priority: Explicitly set APP_PUBLIC_URL in .env
        // This allows manual override if auto-detection fails or for specific setups
        $envPublicUrl = env('APP_PUBLIC_URL');
        if ($envPublicUrl) {
            return rtrim($envPublicUrl, '/') . '/assets/public/' . $this->qr_token;
        }

        // 2. Priority: Use APP_URL from .env if it's not localhost
        // This is useful for production deployments with a fixed domain
        $appUrl = config('app.url');
        if ($appUrl && !str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
            return rtrim($appUrl, '/') . '/assets/public/' . $this->qr_token;
        }

        // 3. Priority: Auto-detect from current request
        // This handles cases where the user accesses the site via IP or a specific domain
        $baseUrl = request()->root(); // e.g., http://192.168.1.100/assect/public
        $host = request()->getHost();

        // 4. Fallback: If accessed via localhost, try to find the actual LAN IP
        // This solves the problem of printing QR codes while sitting at the server
        if (in_array($host, ['localhost', '127.0.0.1', '::1'])) {
            $lanIp = $this->getLanIp();
            if ($lanIp) {
                // Replace localhost with the detected LAN IP
                $baseUrl = str_replace($host, $lanIp, $baseUrl);
            }
        }

        return rtrim($baseUrl, '/') . '/assets/public/' . $this->qr_token;
    }

    private function getLanIp(): ?string
    {
        $possibleIps = [];

        // 1. Try PHP's built-in hostname detection
        try {
            $hostName = gethostname();
            if ($hostName) {
                $ip = gethostbyname($hostName);
                if ($ip && !in_array($ip, ['127.0.0.1', '::1', $hostName])) {
                    $possibleIps[] = $ip;
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }

        // 2. From server variables (if accessed via a real IP)
        if (!empty($_SERVER['SERVER_ADDR']) && !in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1'])) {
            $possibleIps[] = $_SERVER['SERVER_ADDR'];
        }

        // 3. From network interfaces (OS specific commands)
        if (function_exists('exec')) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows: Use ipconfig
                @exec("ipconfig", $output);
                foreach ($output as $line) {
                    if (str_contains($line, 'IPv4') && preg_match('/(\d+\.\d+\.\d+\.\d+)/', $line, $matches)) {
                        $ip = $matches[1];
                        if (!str_starts_with($ip, '127.') && !str_starts_with($ip, '169.254.')) {
                            $possibleIps[] = $ip;
                        }
                    }
                }
            } else {
                // Linux/Mac: Use hostname -I
                @exec("hostname -I 2>/dev/null", $output);
                if (!empty($output[0])) {
                    $ips = explode(' ', trim($output[0]));
                    foreach ($ips as $ip) {
                        if (!str_starts_with($ip, '127.') && !str_starts_with($ip, '169.254.')) {
                            $possibleIps[] = $ip;
                        }
                    }
                }
            }
        }

        // 4. Filter and return the most likely LAN IP
        // We prefer 192.168.x.x or 10.x.x.x
        foreach (array_unique($possibleIps) as $ip) {
            if (str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.')) {
                return $ip;
            }
        }

        return !empty($possibleIps) ? $possibleIps[0] : null;
    }
}
