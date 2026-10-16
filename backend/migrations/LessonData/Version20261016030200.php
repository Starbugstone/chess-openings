<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Grünfeld Defense revision 2: keeps both revision 1 lines and adds one complete named pinned path. */
final class Version20261016030200 extends AbstractMigration
{
    public const PAWN_GRAB_SOURCE = '1. d4 Nf6 2. c4 g6 3. Nc3 d5 4. cxd5 Nxd5 5. e4 Nxc3 6. bxc3 Bg7 7. Nf3 c5 8. Rb1 O-O 9. Be2 Nc6 10. d5 Bxc3+';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['exchange-spassky' => [26, 0], 'russian-keres' => [21, 1]];

    public const PAWN_GRAB_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/5np1/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkb1r/ppp1pp1p/5np1/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/5np1/3P4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f6d5', 'san' => 'Nxd5', 'fromKey' => 'rnbqkb1r/ppp1pp1p/5np1/3P4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/6p1/3n4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkb1r/ppp1pp1p/6p1/3n4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/6p1/3n4/3PP3/2N5/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd5c3', 'san' => 'Nxc3', 'fromKey' => 'rnbqkb1r/ppp1pp1p/6p1/3n4/3PP3/2N5/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/6p1/8/3PP3/2n5/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'b2c3', 'san' => 'bxc3', 'fromKey' => 'rnbqkb1r/ppp1pp1p/6p1/8/3PP3/2n5/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pp1p/6p1/8/3PP3/2P5/P4PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/ppp1pp1p/6p1/8/3PP3/2P5/P4PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/6p1/8/3PP3/2P5/P4PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1ppbp/6p1/8/3PP3/2P5/P4PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/6p1/8/3PP3/2P2N2/P4PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqk2r/ppp1ppbp/6p1/8/3PP3/2P2N2/P4PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqk2r/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'a1b1', 'san' => 'Rb1', 'fromKey' => 'rnbqk2r/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqk2r/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/1RBQKB1R b Kkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/1RBQKB1R b Kkq -', 'toKey' => 'rnbq1rk1/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/1RBQKB1R w K -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbq1rk1/pp2ppbp/6p1/2p5/3PP3/2P2N2/P4PPP/1RBQKB1R w K -', 'toKey' => 'rnbq1rk1/pp2ppbp/6p1/2p5/3PP3/2P2N2/P3BPPP/1RBQK2R b K -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbq1rk1/pp2ppbp/6p1/2p5/3PP3/2P2N2/P3BPPP/1RBQK2R b K -', 'toKey' => 'r1bq1rk1/pp2ppbp/2n3p1/2p5/3PP3/2P2N2/P3BPPP/1RBQK2R w K -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'r1bq1rk1/pp2ppbp/2n3p1/2p5/3PP3/2P2N2/P3BPPP/1RBQK2R w K -', 'toKey' => 'r1bq1rk1/pp2ppbp/2n3p1/2pP4/4P3/2P2N2/P3BPPP/1RBQK2R b K -'],
        ['uci' => 'g7c3', 'san' => 'Bxc3+', 'fromKey' => 'r1bq1rk1/pp2ppbp/2n3p1/2pP4/4P3/2P2N2/P3BPPP/1RBQK2R b K -', 'toKey' => 'r1bq1rk1/pp2pp1p/2n3p1/2pP4/4P3/2b2N2/P3BPPP/1RBQK2R w K -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Grünfeld Defense revision 2: add a complete named line (modern-exchange-pawn-grab)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. d4 and 2. c4. In the Grünfeld Defense you develop with ...Nf6 and ...g6 and then strike with ...d5. You let White build a big pawn centre and attack it with pieces: the bishop on g7, the knights and the ...c5 break all aim at d4. In the Exchange Variation, Spassky Variation, White takes on d5 and builds a centre with c3, d4 and e4, and you press d4 with ...c5, ...Nc6 and ...Bg4. In the Russian Variation, Keres Variation, White\'s queen goes to b3 and c4, and you take on c4, castle and chase the queen with ...Nfd7-b6 while lining up on d4. In the Modern Exchange Variation, Pawn Grab Line, White develops with Nf3 and Rb1, and after d4-d5 you take the c3 pawn with check. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['modern-exchange-pawn-grab', 'Modern Exchange: Nf3 and Rb1, then ...Bxc3+ takes a pawn', 'Ten Black moves. Exchange knights on c3, meet Nf3 with ...c5, castle and add ...Nc6 against d4, then take the c3 pawn with check when White advances d4-d5. Source: Exchange Variation, Modern Exchange Variation, Pawn Grab Line.', 2, self::PAWN_GRAB_SOURCE, self::PAWN_GRAB_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight towards the centre, where it controls e4 and d5. You keep your d-pawn back for now and let White show a setup first.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['g6', 'Move your pawn from g7 to g6.', 'Prepare to fianchetto your bishop on g7. Once your knight on f6 moves, the bishop will work along the long diagonal towards d4.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Strike at the centre with the d-pawn: this is the Grünfeld Defense. Your pawn attacks c4 and controls e4. If White exchanges on d5 and builds a big pawn centre, you will attack that centre with your pieces and the ...c5 break.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White captures on d5: this is where the Exchange Variation chooses its own path. White removes your central pawn and gets ready to gain time against your knight with e4.'],
                ['Nxd5', 'Move your knight from f6 to d5, capturing the white pawn.', 'Recapture with the knight and restore the material balance. Your knight stands in the centre for now, but White\'s e-pawn can attack it.'],
                ['e4', 'Move your pawn from e2 to e4.', 'White attacks your knight with the e-pawn and occupies the centre with pawns on d4 and e4.'],
                ['Nxc3', 'Move your knight from d5 to c3, capturing the white knight.', 'Exchange knights instead of retreating. Your knight on c3 also attacks the queen on d1, so White must recapture at once, and only the b-pawn can do it.'],
                ['bxc3', 'Move your pawn from b2 to c3, capturing the black knight.', 'White recaptures with the b-pawn. The pawns on c3, d4 and e4 form a broad centre, but the pawn on a2 is now isolated and the centre has to be defended against your pieces.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Develop the bishop on the long diagonal. With your knight gone from f6, it already attacks d4, and behind that pawn lie c3 and the rook on a1. Your king is also ready to castle.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight to f3 instead of playing Bc4: this is the Modern Exchange Variation. The knight defends d4 directly, and White keeps the bishop on f1 at home for now.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Strike at d4 with your c-pawn, the central idea of the Grünfeld. Your pawn, bishop and queen attack d4; White\'s c3 pawn, knight and queen defend it.'],
                ['Rb1', 'Move your rook from a1 to b1.', 'White moves the rook to the half-open b-file, where it attacks your b7 pawn. Only your bishop on c8 defends that pawn, so the bishop is tied to it for the moment.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle your king into safety. The b7 pawn is still defended by your bishop, so you can finish your kingside development first.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop to e2, where it supports the knight on f3, and prepares to castle.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and attack d4 a fourth time. Four of your units now hit d4 and three of White\'s defend it, so White has to act in the centre.'],
                ['d5', 'Move your pawn from d4 to d5.', 'White advances with d4-d5 and attacks your knight on c6, which your b7 pawn defends. With the d-pawn gone from d4, the long diagonal is open: your bishop on g7 now attacks the c3 pawn, which nothing defends.'],
                ['Bxc3+', 'Move your bishop from g7 to c3, capturing the white pawn with check.', 'Take the c3 pawn with check. You are a pawn up, and White must answer the check first. Your knight on c6 is still attacked by the d5 pawn, and with your bishop gone from g7, the squares f6 and h6 near your king have lost a defender. The named Pawn Grab Line ends here: your next tasks are to save the knight, decide what to do with your bishop on c3 once White has dealt with the check, and keep the extra pawn.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='grunfeld'")) {
            throw new \RuntimeException('Grünfeld Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='grunfeld' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Grünfeld Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Grünfeld Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='grunfeld' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='grunfeld' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='grunfeld' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('grunfeld',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('grunfeld',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Grünfeld Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='grunfeld'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Grünfeld Defense sessions and progress.');
    }
}
