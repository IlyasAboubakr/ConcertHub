<?php
$user = App\Models\User::factory()->create(['role' => 'organizer', 'artist_name' => 'Test Artist']);
$event = App\Models\Event::create([
    'title' => 'Test Event',
    'location' => 'Test Location',
    'event_date' => now(),
    'event_time' => now(),
    'organizer_id' => $user->id
]);
$tt = App\Models\TicketType::create([
    'event_id' => $event->id,
    'name' => 'VIP',
    'price' => 100,
    'quantity_available' => 10
]);

$out = "Before delete: Event deleted_at: " . ($event->fresh()->deleted_at ?? 'null') . "\n";
$out .= "Before delete: TicketType deleted_at: " . ($tt->fresh()->deleted_at ?? 'null') . "\n";

$user->delete();

$out .= "After delete: Event deleted_at: " . ($event->fresh()->deleted_at ?? 'null') . "\n";
$out .= "After delete: TicketType deleted_at: " . ($tt->fresh()->deleted_at ?? 'null') . "\n";

$user->restore();

$out .= "After restore: Event deleted_at: " . ($event->fresh()->deleted_at ?? 'null') . "\n";
$out .= "After restore: TicketType deleted_at: " . ($tt->fresh()->deleted_at ?? 'null') . "\n";

// Cleanup (hard delete)
$user->forceDelete();
$event->forceDelete();
$tt->forceDelete();
$out .= "Cleanup done.\n";
file_put_contents('cascades_result.txt', $out);

