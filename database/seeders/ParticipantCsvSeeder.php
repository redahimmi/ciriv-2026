<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;

class ParticipantCsvSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen('/Users/reda/Downloads/noms_emails_candidats.csv', 'r');
        fgetcsv($file); // skip header

        $inserted = 0;
        $updated  = 0;

        while ($row = fgetcsv($file)) {
            $nom    = trim($row[0] ?? '');
            $prenom = trim($row[1] ?? '');
            $email  = strtolower(trim($row[2] ?? ''));

            if (!$email) continue;

            $name = trim($prenom . ' ' . $nom) ?: null;

            $existing = Participant::where('email', $email)->first();
            if ($existing) {
                $existing->update(['name' => $name]);
                $updated++;
            } else {
                Participant::create(['email' => $email, 'name' => $name]);
                $inserted++;
            }
        }

        fclose($file);

        $total = Participant::count();
        echo "Insérés: $inserted | Mis à jour: $updated | Total en base: $total\n";
    }
}
