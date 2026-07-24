<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\Game;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public const DEMO_EMAIL = "demo@boardgameapp.dev";
    public const DEMO_PASSWORD = "DemoPass123!";

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ["email" => self::DEMO_EMAIL],
            [
                "name" => "Demo User",
                "password" => Hash::make(self::DEMO_PASSWORD),
                "email_verified_at" => now(),
            ],
        );

        // Wipe anything added/changed since the last reset (spam entries, edited
        // demo data, ...) so the public demo always comes back to a clean state.
        // Friend/session pivot rows cascade-delete with their parent row.
        Game::where("user_id", $user->id)->delete();
        Friend::where("user_id", $user->id)->delete();
        Session::where("user_id", $user->id)->delete();

        $games = $this->seedGames($user);
        $friends = $this->seedFriends($user);

        $this->seedPreferences($friends, $games);
        $this->seedSessions($user, $friends, $games);
    }

    /**
     * @return array<string, Game>
     */
    private function seedGames(User $user): array
    {
        $definitions = [
            ["name" => "Osadnicy z Catanu", "min_players" => 3, "max_players" => 4, "year" => 1995, "copies" => 1, "is_shared" => true, "description" => "Klasyczna gra o budowaniu osad i handlu surowcami na wyspie Catan."],
            ["name" => "Pandemia", "min_players" => 2, "max_players" => 4, "year" => 2008, "copies" => 1, "is_shared" => true, "description" => "Kooperacyjna gra, w której drużyna naukowców ratuje świat przed czterema epidemiami."],
            ["name" => "Carcassonne", "min_players" => 2, "max_players" => 5, "year" => 2000, "copies" => 2, "is_shared" => true, "description" => "Układanie krajobrazu z płytek: miast, dróg i klasztorów w średniowiecznej Francji."],
            ["name" => "Dixit", "min_players" => 3, "max_players" => 8, "year" => 2008, "copies" => 1, "is_shared" => true, "description" => "Gra imprezowa o skojarzeniach z pięknie ilustrowanymi kartami."],
            ["name" => "Splendor", "min_players" => 2, "max_players" => 4, "year" => 2014, "copies" => 1, "is_shared" => false, "description" => "Szybka gra ekonomiczna o zbieraniu klejnotów i rozwijaniu manufaktury renesansowego kupca."],
            ["name" => "Azul", "min_players" => 2, "max_players" => 4, "year" => 2017, "copies" => 1, "is_shared" => true, "description" => "Układanie kolorowych kafelków w stylu portugalskich azulejos."],
            ["name" => "Na Skrzydłach", "min_players" => 1, "max_players" => 5, "year" => 2019, "copies" => 1, "is_shared" => false, "description" => "Budowanie rezerwatu ptaków w eleganckiej grze karcianej o zbieraniu zestawów."],
            ["name" => "7 Cudów Świata", "min_players" => 2, "max_players" => 7, "year" => 2010, "copies" => 1, "is_shared" => true, "description" => "Rozwijanie starożytnej cywilizacji na przestrzeni trzech epok."],
            ["name" => "Terraformacja Marsa", "min_players" => 1, "max_players" => 5, "year" => 2016, "copies" => 1, "is_shared" => false, "description" => "Korporacje rywalizują o uczynienie Marsa zdatnym do zamieszkania."],
            ["name" => "Kosa", "min_players" => 1, "max_players" => 5, "year" => 2016, "copies" => 1, "is_shared" => true, "description" => "Alternatywna historia lat 20. XX wieku z gigantycznymi mechami i walką o terytorium."],
            ["name" => "Tajniacy", "min_players" => 2, "max_players" => 8, "year" => 2015, "copies" => 2, "is_shared" => true, "description" => "Drużynowa gra słowna o podawaniu jednowyrazowych podpowiedzi."],
            ["name" => "Wsiąść do Pociągu", "min_players" => 2, "max_players" => 5, "year" => 2004, "copies" => 1, "is_shared" => false, "description" => "Budowanie sieci kolejowych i zbieranie biletów łączących miasta."],
        ];

        $games = [];

        foreach ($definitions as $definition) {
            $games[$definition["name"]] = Game::updateOrCreate(
                ["user_id" => $user->id, "name" => $definition["name"]],
                [...$definition, "user_id" => $user->id],
            );
        }

        return $games;
    }

    /**
     * @return array<string, Friend>
     */
    private function seedFriends(User $user): array
    {
        $definitions = [
            ["first_name" => "Kasia", "last_name" => "Nowak", "email" => "kasia.nowak@example.com"],
            ["first_name" => "Marek", "last_name" => "Kowalski", "email" => "marek.kowalski@example.com"],
            ["first_name" => "Ola", "last_name" => "Wiśniewska", "email" => "ola.wisniewska@example.com"],
            ["first_name" => "Tomek", "last_name" => "Zieliński", "email" => "tomek.zielinski@example.com"],
            ["first_name" => "Ania", "last_name" => "Wójcik", "email" => "ania.wojcik@example.com"],
            ["first_name" => "Piotr", "last_name" => "Lewandowski", "email" => "piotr.lewandowski@example.com"],
        ];

        $friends = [];

        foreach ($definitions as $definition) {
            $friends[$definition["first_name"]] = Friend::updateOrCreate(
                ["user_id" => $user->id, "email" => $definition["email"]],
                [...$definition, "user_id" => $user->id],
            );
        }

        return $friends;
    }

    /**
     * @param array<string, Friend> $friends
     * @param array<string, Game> $games
     */
    private function seedPreferences(array $friends, array $games): void
    {
        $ratings = [
            "Kasia" => ["Dixit" => 9, "Azul" => 8, "Tajniacy" => 7, "Carcassonne" => 6],
            "Marek" => ["Kosa" => 10, "Terraformacja Marsa" => 9, "7 Cudów Świata" => 8, "Osadnicy z Catanu" => 7],
            "Ola" => ["Azul" => 9, "Splendor" => 8, "Na Skrzydłach" => 8, "Dixit" => 6],
            "Tomek" => ["Pandemia" => 9, "Terraformacja Marsa" => 8, "Kosa" => 7, "Wsiąść do Pociągu" => 7],
            "Ania" => ["Tajniacy" => 10, "Dixit" => 8, "Carcassonne" => 7, "Azul" => 7],
            "Piotr" => ["7 Cudów Świata" => 9, "Osadnicy z Catanu" => 8, "Splendor" => 6, "Kosa" => 8],
        ];

        foreach ($ratings as $friendName => $gameRatings) {
            $friend = $friends[$friendName];

            $syncData = [];

            foreach ($gameRatings as $gameName => $rating) {
                $syncData[$games[$gameName]->id] = ["rating" => $rating];
            }

            $friend->games()->sync($syncData);
        }
    }

    /**
     * @param array<string, Friend> $friends
     * @param array<string, Game> $games
     */
    private function seedSessions(User $user, array $friends, array $games): void
    {
        $definitions = [
            [
                "name" => "Piątkowy wieczór planszówek",
                "date" => now()->subWeeks(6),
                "notes" => "Pierwsza rozgrywka w Kosę, gra ciągnęła się prawie 3 godziny, ale warto.",
                "friends" => ["Marek", "Tomek", "Piotr"],
                "games" => ["Kosa"],
            ],
            [
                "name" => "Rodzinne popołudnie",
                "date" => now()->subWeeks(4),
                "notes" => "Lekkie gry na rozgrzewkę przed obiadem.",
                "friends" => ["Kasia", "Ania"],
                "games" => ["Azul", "Carcassonne"],
            ],
            [
                "name" => "Maraton kooperacyjny",
                "date" => now()->subWeeks(3),
                "notes" => "Udało się uratować świat za trzecim podejściem!",
                "friends" => ["Ola", "Tomek", "Kasia"],
                "games" => ["Pandemia"],
            ],
            [
                "name" => "Wieczór imprezowy",
                "date" => now()->subWeeks(2),
                "notes" => "Dixit i Tajniacy do białego rana, dużo śmiechu.",
                "friends" => ["Kasia", "Ania", "Ola", "Piotr"],
                "games" => ["Dixit", "Tajniacy"],
            ],
            [
                "name" => "Sesja strategiczna",
                "date" => now()->subWeek(),
                "notes" => "Pierwszy raz Terraformacja Marsa na czterech graczy - świetnie się skaluje.",
                "friends" => ["Marek", "Tomek"],
                "games" => ["Terraformacja Marsa", "7 Cudów Świata"],
            ],
            [
                "name" => "Niedzielne granie",
                "date" => now()->subDays(2),
                "notes" => "Krótka sesja przed wyjazdem, zdążyliśmy zagrać dwie partie Splendora.",
                "friends" => ["Ola", "Piotr"],
                "games" => ["Splendor"],
            ],
        ];

        foreach ($definitions as $definition) {
            $session = Session::updateOrCreate(
                ["user_id" => $user->id, "name" => $definition["name"]],
                [
                    "user_id" => $user->id,
                    "name" => $definition["name"],
                    "date" => $definition["date"],
                    "notes" => $definition["notes"],
                ],
            );

            $friendIds = collect($definition["friends"])
                ->map(fn(string $name) => $friends[$name]->id)
                ->all();

            $gameIds = collect($definition["games"])
                ->map(fn(string $name) => $games[$name]->id)
                ->all();

            $session->friends()->sync($friendIds);
            $session->games()->sync($gameIds);
        }
    }
}
