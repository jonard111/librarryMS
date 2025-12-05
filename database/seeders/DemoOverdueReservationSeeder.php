<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookReservation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * DemoOverdueReservationSeeder
 * 
 * Creates sample reservation data for testing fine calculation:
 * - 1 reservation that is 1 day overdue (daily loan)
 * - 1 reservation that is 5 hours overdue (hourly loan)
 */
class DemoOverdueReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get or create a student user for testing
        $student = User::where('role', 'student')->first();
        
        if (!$student) {
            // Create a test student if none exists
            $student = User::create([
                'first_name' => 'Test',
                'last_name' => 'Student',
                'email' => 'teststudent@example.com',
                'password' => bcrypt('password'),
                'role' => 'student',
                'account_status' => 'approved',
                'registration_date' => Carbon::now()->subMonths(2),
            ]);
        }

        // Get or create a book for testing
        $book = Book::first();
        
        if (!$book) {
            // Create a test book if none exists
            $book = Book::create([
                'title' => 'Sample Book for Testing',
                'author' => 'Test Author',
                'category' => 'education',
                'copies' => 5,
                'isbn' => '978-0-123456-78-9',
                'publisher' => 'Test Publisher',
            ]);
        }

        // Create a reservation that is 1 day overdue (daily loan)
        // This should result in ₱10.00 fine (1 day × ₱10.00)
        $overdueDailyReservation = BookReservation::updateOrCreate(
            [
                'user_id' => $student->userId,
                'book_id' => $book->id,
                'status' => 'picked_up',
            ],
            [
                'reservation_date' => Carbon::now()->subDays(8), // Reserved 8 days ago
                'pickup_date' => Carbon::now()->subDays(7), // Picked up 7 days ago
                'due_date' => Carbon::now()->subDay(), // Due date was yesterday (1 day overdue)
                'return_date' => null, // Not returned yet
                'loan_duration' => 7, // 7 days loan
                'loan_duration_unit' => 'day',
                'status' => 'picked_up',
                'fine_amount' => 0, // Not settled yet
                'fine_paid_at' => null, // Not paid yet
                'notes' => 'Sample overdue reservation - 1 day late (Daily loan)',
            ]
        );

        // Create a reservation that is 5 hours overdue (hourly loan)
        // This should result in ₱5.00 fine (5 hours × ₱1.00)
        $overdueHourlyReservation = BookReservation::updateOrCreate(
            [
                'user_id' => $student->userId,
                'book_id' => $book->id,
                'status' => 'picked_up',
                'loan_duration_unit' => 'hour',
            ],
            [
                'reservation_date' => Carbon::now()->subHours(30), // Reserved 30 hours ago
                'pickup_date' => Carbon::now()->subHours(25), // Picked up 25 hours ago
                'due_date' => Carbon::now()->subHours(5), // Due date was 5 hours ago (5 hours overdue)
                'return_date' => null, // Not returned yet
                'loan_duration' => 20, // 20 hours loan
                'loan_duration_unit' => 'hour',
                'status' => 'picked_up',
                'fine_amount' => 0, // Not settled yet
                'fine_paid_at' => null, // Not paid yet
                'notes' => 'Sample overdue reservation - 5 hours late (Hourly loan)',
            ]
        );

        $this->command->info('Sample overdue reservations created:');
        $this->command->info('- Daily loan: 1 day overdue (Expected fine: ₱10.00)');
        $this->command->info('- Hourly loan: 5 hours overdue (Expected fine: ₱5.00)');
    }
}

