<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Four Knights Game revision 2: keeps the three revision 1 lines and appends one complete named pinned path. */
final class Version20261016031100 extends AbstractMigration
{
    public const BARDELEBEN_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Nxe5 7. d4 Bd6 8. f4 Nc6 9. e5 Bb4';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['scotch-four-knights' => [17, 0], 'spanish-symmetrical' => [20, 1], 'rubinstein-maroczy' => [18, 2]];

    public const BARDELEBEN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/2N2N2/PPPP1PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/4P3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1b5', 'san' => 'Bb5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/4P3/2N2N2/PPPP1PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R b KQkq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/2N2N2/PPPP1PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQ1RK1 b kq -', 'toKey' => 'r1bq1rk1/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQ1RK1 w - -'],
        ['uci' => 'f3e5', 'san' => 'Nxe5', 'fromKey' => 'r1bq1rk1/pppp1ppp/2n2n2/1Bb1p3/4P3/2N2N2/PPPP1PPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppp1ppp/2n2n2/1Bb1N3/4P3/2N5/PPPP1PPP/R1BQ1RK1 b - -'],
        ['uci' => 'c6e5', 'san' => 'Nxe5', 'fromKey' => 'r1bq1rk1/pppp1ppp/2n2n2/1Bb1N3/4P3/2N5/PPPP1PPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppp1ppp/5n2/1Bb1n3/4P3/2N5/PPPP1PPP/R1BQ1RK1 w - -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bq1rk1/pppp1ppp/5n2/1Bb1n3/4P3/2N5/PPPP1PPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppp1ppp/5n2/1Bb1n3/3PP3/2N5/PPP2PPP/R1BQ1RK1 b - -'],
        ['uci' => 'c5d6', 'san' => 'Bd6', 'fromKey' => 'r1bq1rk1/pppp1ppp/5n2/1Bb1n3/3PP3/2N5/PPP2PPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppp1ppp/3b1n2/1B2n3/3PP3/2N5/PPP2PPP/R1BQ1RK1 w - -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'r1bq1rk1/pppp1ppp/3b1n2/1B2n3/3PP3/2N5/PPP2PPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppp1ppp/3b1n2/1B2n3/3PPP2/2N5/PPP3PP/R1BQ1RK1 b - -'],
        ['uci' => 'e5c6', 'san' => 'Nc6', 'fromKey' => 'r1bq1rk1/pppp1ppp/3b1n2/1B2n3/3PPP2/2N5/PPP3PP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppp1ppp/2nb1n2/1B6/3PPP2/2N5/PPP3PP/R1BQ1RK1 w - -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'r1bq1rk1/pppp1ppp/2nb1n2/1B6/3PPP2/2N5/PPP3PP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppp1ppp/2nb1n2/1B2P3/3P1P2/2N5/PPP3PP/R1BQ1RK1 b - -'],
        ['uci' => 'd6b4', 'san' => 'Bb4', 'fromKey' => 'r1bq1rk1/pppp1ppp/2nb1n2/1B2P3/3P1P2/2N5/PPP3PP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppp1ppp/2n2n2/1B2P3/1b1P1P2/2N5/PPP3PP/R1BQ1RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Four Knights Game revision 2: keep three lines, append the Bardeleben Variation (classical-bardeleben)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Learn the Four Knights Game as White. After 1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 all four knights are developed towards the centre, and your plan is simple: bring out the bishops, castle and choose the right moment for a central break. In the Scotch Four Knights, you open the centre with 4. d4 and answer the pin ...Bb4 by exchanging knights on c6. In the Symmetrical Variation, both sides play Bb5 and ...Bb4, and you break the symmetry with Bg5, Re1 and d4. In the Rubinstein Variation, Black answers 4. Bb5 with ...Nd4 and you keep your pieces together with Be2 and Bxf3. In the Bardeleben Variation, Black answers with ...Bc5: you give a knight for e5 and chase Black\'s pieces with pawn forks. Black replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['classical-bardeleben', 'Bardeleben: Nxe5 and two pawn forks against ...Bc5', 'Nine White moves. Against ...Bc5, castle, give a knight for the e5 pawn, then use d4, f4 and e5 to attack Black\'s pieces and set up winning the piece back. Source: Spanish Variation, Bardeleben Variation.', 3, self::BARDELEBEN_SOURCE, self::BARDELEBEN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open paths for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing further.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight with a threat: it attacks the pawn on e5. It also clears the way for castling.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 by developing a knight.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'Develop the second knight. It defends e4 and controls d5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops the last knight and attacks e4. All four knights are now in play, which gives the opening its name.'],
                ['Bb5', 'Move your bishop from f1 to b5.', 'Develop the bishop to b5 and attack the knight on c6, the piece that defends e5. This is the Spanish Four Knights: here the line takes a different path from the Scotch Four Knights, which plays d4 instead.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Black develops the bishop to c5, where it attacks your pawn on f2. Here this line takes its own path: this is the Classical Variation. The pawn on e5 is still defended only by the knight on c6, the piece your bishop attacks.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle. Your king is safe, and the rook on f1 adds a defender to f2, which Black\'s bishop now pins to your king.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles too. The e5 pawn still has only the knight on c6 to defend it.'],
                ['Nxe5', 'Move your knight from f3 to e5, capturing the black pawn.', 'Take the e5 pawn. Your knight attacks the knight on c6 and the pawns on d7 and f7. If Black takes your knight, the d4 advance will attack Black\'s bishop on c5 and knight on e5 at the same time; that central fork is the idea behind giving up the knight.'],
                ['Nxe5', 'Move your knight from c6 to e5, capturing the white knight.', 'Black takes your knight. For the moment you have given up a knight for a pawn.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Fork Black\'s bishop and knight with the pawn. Neither piece is defended, so Black needs a move that deals with both at once.'],
                ['Bd6', 'Move your bishop from c5 to d6.', 'Black finds that move: the bishop retreats to d6, where it defends the knight on e5. You are still a knight for a pawn down.'],
                ['f4', 'Move your pawn from f2 to f4.', 'Attack the knight a second time. Two of your pawns now attack it and only the bishop defends it, so it has to move. Once it does, Black\'s bishop on d6 will attack your f4 pawn, which your bishop on c1 and rook on f1 defend.'],
                ['Nc6', 'Move your knight from e5 to c6.', 'The knight retreats to c6, where it attacks your pawn on d4. Only your queen defends d4 at the moment.'],
                ['e5', 'Move your pawn from e4 to e5.', 'Push again: your e-pawn now forks the bishop on d6 and the knight on f6. The pawns on d4 and f4 defend it.'],
                ['Bb4', 'Move your bishop from d6 to b4.', 'Black saves the bishop with a move that attacks your knight on c3, which the b2 pawn defends. The knight on f6 is still attacked by your e5 pawn. The named Bardeleben Variation ends here. You are still a knight for a pawn down, but you can capture on f6 and win the piece back. Your next tasks are to regain the piece, keep your knight on c3 and the d4 pawn protected, and develop your bishop on c1.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='four-knights'")) {
            throw new \RuntimeException('Four Knights Game revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='four-knights' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Four Knights Game revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Four Knights Game teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='four-knights' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='four-knights' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='four-knights' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('four-knights',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('four-knights',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Four Knights Game edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='four-knights'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Four Knights Game sessions and progress.');
    }
}
