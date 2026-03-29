<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@concerthub.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        $organizer = User::create([
            'name' => 'John Doe',
            'email' => 'organizer@concerthub.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'artist_name' => 'The Midnight Echoes'
        ]);

        $organizer2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@concerthub.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'artist_name' => 'Starlight Symphony'
        ]);

        $client = User::create([
            'name' => 'Test Client',
            'email' => 'client@concerthub.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Demo Events
        $events = [
            [
                'title' => 'Neon Nights Festival',
                'description' => "Experience the ultimate electronic dance music festival. Join us for a night of neon lights, incredible drops, and unforgettable memories.",
                'location' => 'Downtown Arena, City Center',
                'event_date' => Carbon::now()->addDays(15),
                'event_time' => '20:00:00',
                'image' => 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'organizer_id' => $organizer->id,
                'status' => 'published',
            ],
            [
                'title' => 'Acoustic Sunset Sessions',
                'description' => "Relax to the soothing sounds of unplugged acoustic performances as the sun goes down.",
                'location' => 'Open Air Amphitheater, Westside Park',
                'event_date' => Carbon::now()->addDays(5),
                'event_time' => '17:30:00',
                'image' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'organizer_id' => $organizer2->id,
                'status' => 'published',
            ],
            [
                'title' => 'Rock the Block',
                'description' => "Get ready to rock out! Local and national bands coming together for an explosive rock concert.",
                'location' => 'The Grand Stadium',
                'event_date' => Carbon::now()->addDays(30),
                'event_time' => '19:00:00',
                'image' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'organizer_id' => $organizer->id,
                'status' => 'published',
            ]
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            // Add ticket types
            TicketType::create([
                'event_id' => $event->id,
                'name' => 'General Admission',
                'price' => 45.00,
                'quantity_available' => 500,
            ]);

            TicketType::create([
                'event_id' => $event->id,
                'name' => 'VIP Pass',
                'price' => 120.00,
                'quantity_available' => 50,
            ]);
        }
    }
}
