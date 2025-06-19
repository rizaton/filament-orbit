<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LateScheduler
{
    public function __invoke(): void
    {
        $this->run();
    }
    private function scheduleLateTasks(): void
    {
        try {
            $status = DB::table('rentals')
                ->where('return_date', '<', now())
                ->where('status', '!=', 'returned')
                ->update(['status' => 'late']);
            Log::info("Scheduled late tasks successfully executed.", [
                'affected_rows' => $status,
                'timestamp' => now(),
            ]);
        } catch (\Throwable $th) {
            Log::alert('Error scheduling late tasks: ' . $th->getMessage(), [
                'exception' => $th,
                'timestamp' => now(),
            ]);
        }
    }

    public function run(): void
    {
        $this->scheduleLateTasks();
    }
}
