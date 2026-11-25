<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * DATA CONTOH SEMENTARA (route Asia Tenggara -> Eropa).
     * Ganti sesuai konfigurasi dari dosen jika sudah ada.
     */
    public function run(): void
    {
        // ---------- Users (password demo: "password") ----------
        $this->makeUser('Admin CargoTrace', 'admin@cargotrace.test', User::ROLE_ADMIN);
        $operator = $this->makeUser('Operator Logistik', 'operator@cargotrace.test', User::ROLE_OPERATOR);
        $customers = [
            $this->makeUser('PT Maju Jaya', 'customer1@cargotrace.test', User::ROLE_CUSTOMER),
            $this->makeUser('CV Sinar Dunia', 'customer2@cargotrace.test', User::ROLE_CUSTOMER),
        ];

        // ---------- Carriers ----------
        $carrierData = [
            'fastship' => ['name' => 'FastShip', 'code' => 'FSH', 'contact_email' => 'ops@fastship.test'],
            'oceanlink' => ['name' => 'OceanLink', 'code' => 'OCL', 'contact_email' => 'ops@oceanlink.test'],
            'asiacargo' => ['name' => 'AsiaCargo Lines', 'code' => 'ACL', 'contact_email' => 'ops@asiacargo.test'],
        ];
        $carriers = [];
        foreach ($carrierData as $key => $data) {
            $carriers[$key] = Carrier::create($data);
        }

        // ---------- Locations ----------
        $locationData = [
            'jakarta' => ['name' => 'Tanjung Priok Port', 'city' => 'Jakarta', 'country' => 'Indonesia', 'latitude' => -6.1046, 'longitude' => 106.8800],
            'singapore' => ['name' => 'Port of Singapore', 'city' => 'Singapore', 'country' => 'Singapore', 'latitude' => 1.2644, 'longitude' => 103.8222],
            'colombo' => ['name' => 'Port of Colombo', 'city' => 'Colombo', 'country' => 'Sri Lanka', 'latitude' => 6.9497, 'longitude' => 79.8428],
            'rotterdam' => ['name' => 'Port of Rotterdam', 'city' => 'Rotterdam', 'country' => 'Netherlands', 'latitude' => 51.9496, 'longitude' => 4.1427],
            'hamburg' => ['name' => 'Port of Hamburg', 'city' => 'Hamburg', 'country' => 'Germany', 'latitude' => 53.5461, 'longitude' => 9.9661],
        ];
        $locations = [];
        foreach ($locationData as $key => $data) {
            $locations[$key] = Location::create($data);
        }

        // ---------- Shipments ----------
        $steps = ['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED'];

        $plans = [
            ['status' => 'DELIVERED', 'carrier' => 'fastship', 'origin' => 'jakarta', 'dest' => 'rotterdam',
                'cargo' => 'Electronics', 'weight' => 1200, 'volume' => 18.5, 'item' => ['Laptop cartons', 300],
                'started_hours_ago' => 480, 'transit_days' => 10, 'delay_hours' => 0],
            ['status' => 'DELIVERED', 'carrier' => 'oceanlink', 'origin' => 'singapore', 'dest' => 'hamburg',
                'cargo' => 'Textiles', 'weight' => 3400, 'volume' => 42.0, 'item' => ['Cotton fabric rolls', 150],
                'started_hours_ago' => 528, 'transit_days' => 12, 'delay_hours' => 30,
                'exception' => ['type' => 'PORT_CONGESTION', 'severity' => 'LOW', 'status' => 'RESOLVED', 'note' => 'Antrean bongkar muat di pelabuhan transit.']],
            ['status' => 'IN_TRANSIT', 'carrier' => 'asiacargo', 'origin' => 'jakarta', 'dest' => 'hamburg',
                'cargo' => 'Furniture', 'weight' => 5200, 'volume' => 60.0, 'item' => ['Teak chairs', 400],
                'started_hours_ago' => 144, 'transit_days' => 12, 'delay_hours' => 0],
            ['status' => 'IN_TRANSIT', 'carrier' => 'fastship', 'origin' => 'singapore', 'dest' => 'rotterdam',
                'cargo' => 'Auto Parts', 'weight' => 2800, 'volume' => 30.0, 'item' => ['Brake assemblies', 500],
                'started_hours_ago' => 192, 'transit_days' => 14, 'delay_hours' => 10,
                'exception' => ['type' => 'WEATHER_DELAY', 'severity' => 'MEDIUM', 'status' => 'OPEN', 'note' => 'Badai di jalur pelayaran menyebabkan kapal melambat.']],
            ['status' => 'IN_TRANSIT', 'carrier' => 'oceanlink', 'origin' => 'jakarta', 'dest' => 'rotterdam',
                'cargo' => 'Food Packaging', 'weight' => 1800, 'volume' => 25.0, 'item' => ['Plastic containers', 900],
                'started_hours_ago' => 240, 'transit_days' => 13, 'delay_hours' => 30,
                'exception' => ['type' => 'CUSTOMS_HOLD', 'severity' => 'HIGH', 'status' => 'INVESTIGATING', 'note' => 'Dokumen bea cukai sedang diperiksa.']],
            ['status' => 'PICKED_UP', 'carrier' => 'asiacargo', 'origin' => 'singapore', 'dest' => 'hamburg',
                'cargo' => 'Machinery', 'weight' => 6000, 'volume' => 55.0, 'item' => ['Packing machines', 12],
                'started_hours_ago' => 72, 'transit_days' => 15, 'delay_hours' => 0],
            ['status' => 'BOOKED', 'carrier' => 'fastship', 'origin' => 'jakarta', 'dest' => 'rotterdam',
                'cargo' => 'Garments', 'weight' => 900, 'volume' => 14.0, 'item' => ['Shirt cartons', 250],
                'started_hours_ago' => 24, 'transit_days' => 11, 'delay_hours' => 0],
            ['status' => 'CREATED', 'carrier' => 'oceanlink', 'origin' => 'singapore', 'dest' => 'rotterdam',
                'cargo' => 'Chemicals (non-hazardous)', 'weight' => 2200, 'volume' => 28.0, 'item' => ['Resin drums', 80],
                'started_hours_ago' => 6, 'transit_days' => 14, 'delay_hours' => 0],
        ];

        foreach ($plans as $i => $plan) {
            $started = now()->subHours($plan['started_hours_ago']);
            $departure = $started->copy()->addHours(48);
            $expectedArrival = $departure->copy()->addDays($plan['transit_days']);
            $estimatedArrival = $expectedArrival->copy()->addHours($plan['delay_hours']);

            $shipment = Shipment::forceCreate([
                'shipment_number' => sprintf('SHP-2026-%04d', $i + 1),
                'customer_id' => $customers[$i % 2]->id,
                'carrier_id' => $carriers[$plan['carrier']]->id,
                'origin_location_id' => $locations[$plan['origin']]->id,
                'destination_location_id' => $locations[$plan['dest']]->id,
                'cargo_type' => $plan['cargo'],
                'weight_kg' => $plan['weight'],
                'volume_cbm' => $plan['volume'],
                'expected_departure_at' => $departure,
                'expected_arrival_at' => $expectedArrival,
                'estimated_arrival_at' => $estimatedArrival,
                'current_status' => $plan['status'],
                'created_by' => $operator->id,
            ]);

            $shipment->items()->create([
                'description' => $plan['item'][0],
                'quantity' => $plan['item'][1],
                'weight_kg' => $plan['weight'],
            ]);

            // ---------- Tracking events (urut, tidak saling menimpa) ----------
            $eventTimes = [
                'CREATED' => $started,
                'BOOKED' => $started->copy()->addHours(12),
                'PICKED_UP' => $departure,
                'IN_TRANSIT' => $departure->copy()->addHours(12),
                'DELIVERED' => $estimatedArrival,
            ];
            $eventPlaces = [
                'CREATED' => $plan['origin'],
                'BOOKED' => $plan['origin'],
                'PICKED_UP' => $plan['origin'],
                'IN_TRANSIT' => 'colombo',
                'DELIVERED' => $plan['dest'],
            ];
            $eventNotes = [
                'CREATED' => 'Shipment dibuat.',
                'BOOKED' => 'Slot kapal berhasil dipesan.',
                'PICKED_UP' => 'Barang diambil dari gudang asal.',
                'IN_TRANSIT' => 'Barang dalam perjalanan.',
                'DELIVERED' => 'Barang diterima di tujuan.',
            ];

            foreach ($steps as $step) {
                $place = $locations[$eventPlaces[$step]];

                $shipment->trackingEvents()->forceCreate([
                    'location_id' => $place->id,
                    'status' => $step,
                    'occurred_at' => $eventTimes[$step],
                    'latitude' => $place->latitude,
                    'longitude' => $place->longitude,
                    'description' => $eventNotes[$step],
                    'is_exception' => false,
                    'created_by' => $operator->id,
                ]);

                if ($step === $plan['status']) {
                    break;
                }
            }

            // ---------- Exception (opsional) ----------
            if (isset($plan['exception'])) {
                $ex = $plan['exception'];

                $shipment->exceptions()->forceCreate([
                    'type' => $ex['type'],
                    'severity' => $ex['severity'],
                    'status' => $ex['status'],
                    'detected_at' => $departure->copy()->addDays(3),
                    'resolved_at' => $ex['status'] === 'RESOLVED' ? $departure->copy()->addDays(4) : null,
                    'description' => $ex['note'],
                    'created_by' => $operator->id,
                ]);
            }
        }
    }

    private function makeUser(string $name, string $email, string $role): User
    {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
        ]);

        // Role tidak ada di $fillable (anti mass-assignment), jadi diisi manual.
        $user->role = $role;
        $user->save();

        return $user;
    }
}