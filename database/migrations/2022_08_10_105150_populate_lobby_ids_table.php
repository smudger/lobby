<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $firstLetterGroups = $this->generate2LetterIds();
        $secondLetterGroups = $this->generate2LetterIds();

        foreach ($firstLetterGroups as $firstLetterGroup) {
            DB::table('lobby_ids')->insert(
                array_map(fn (string $secondLetterGroup) => [
                    'id' => $firstLetterGroup.$secondLetterGroup,
                ], $secondLetterGroups)
            );
        }
    }

    /**
     * @return string[]
     */
    public function generate2LetterIds(): array
    {
        $ids = [];

        foreach (range('A', 'Z') as $firstLetter) {
            foreach (range('A', 'Z') as $secondLetter) {
                $ids[] = $firstLetter.$secondLetter;
            }
        }

        return $ids;
    }
};
