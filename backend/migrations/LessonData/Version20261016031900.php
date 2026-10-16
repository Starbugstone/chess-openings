<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** New English Opening course: one complete named pinned path with original teaching notes. */
final class Version20261016031900 extends AbstractMigration
{
    public const HEDGEHOG_SOURCE = '1. c4 e6 2. Nf3 c5 3. Nc3 Nf6 4. g3 b6 5. Bg2 Bb7 6. O-O Be7 7. d4 cxd4 8. Qxd4 d6 9. Rd1 a6 10. b3 Nbd7';

    public const HEDGEHOG_FACTS = [
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/2P5/8/PP1PPPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/2P5/8/PP1PPPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2P5/8/PP1PPPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2P5/8/PP1PPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2P5/5N2/PP1PPPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2P5/5N2/PP1PPPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/2P5/5N2/PP1PPPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/2P5/5N2/PP1PPPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/2P5/2N2N2/PP1PPPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/2P5/2N2N2/PP1PPPPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2P5/2N2N2/PP1PPPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2P5/2N2N2/PP1PPPPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2P5/2N2NP1/PP1PPP1P/R1BQKB1R b KQkq -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2P5/2N2NP1/PP1PPP1P/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p2p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPP1P/R1BQKB1R w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'rnbqkb1r/p2p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPP1P/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p2p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQK2R b KQkq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'rnbqkb1r/p2p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQK2R b KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQK2R w KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQ1RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQ1RK1 b kq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQ1RK1 w kq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/2p5/2P5/2N2NP1/PP1PPPBP/R1BQ1RK1 w kq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/2p5/2PP4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/2p5/2PP4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2Pp4/2N2NP1/PP2PPBP/R1BQ1RK1 w kq -'],
        ['uci' => 'd1d4', 'san' => 'Qxd4', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2Pp4/2N2NP1/PP2PPBP/R1BQ1RK1 w kq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PQ4/2N2NP1/PP2PPBP/R1B2RK1 b kq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PQ4/2N2NP1/PP2PPBP/R1B2RK1 b kq -', 'toKey' => 'rn1qk2r/pb2bppp/1p1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1B2RK1 w kq -'],
        ['uci' => 'f1d1', 'san' => 'Rd1', 'fromKey' => 'rn1qk2r/pb2bppp/1p1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1B2RK1 w kq -', 'toKey' => 'rn1qk2r/pb2bppp/1p1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1BR2K1 b kq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'rn1qk2r/pb2bppp/1p1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1BR2K1 b kq -', 'toKey' => 'rn1qk2r/1b2bppp/pp1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1BR2K1 w kq -'],
        ['uci' => 'b2b3', 'san' => 'b3', 'fromKey' => 'rn1qk2r/1b2bppp/pp1ppn2/8/2PQ4/2N2NP1/PP2PPBP/R1BR2K1 w kq -', 'toKey' => 'rn1qk2r/1b2bppp/pp1ppn2/8/2PQ4/1PN2NP1/P3PPBP/R1BR2K1 b kq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rn1qk2r/1b2bppp/pp1ppn2/8/2PQ4/1PN2NP1/P3PPBP/R1BR2K1 b kq -', 'toKey' => 'r2qk2r/1b1nbppp/pp1ppn2/8/2PQ4/1PN2NP1/P3PPBP/R1BR2K1 w kq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish the English Opening course: Symmetrical Hedgehog, Flexible Formation';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Learn the English Opening as White: you start with 1. c4, control d5 from the side and develop flexibly with a kingside fianchetto. This course teaches one complete named line from the opening reference, the Symmetrical Hedgehog. Black answers with ...e6 and ...c5, fianchettoes the bishop on b7 and keeps compact pawns on a6, b6, d6 and e6. You fianchetto, castle, open the centre with d4, recapture with your queen and use Rd1 and b3 to prepare for the ...b5 and ...d5 pawn breaks. Black replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['hedgehog-flexible-formation', 'Symmetrical Hedgehog: d4, Qxd4, Rd1 and b3', "Ten White moves. Fianchetto your king's bishop, open the centre with d4, recapture with the queen and meet Black's compact ...a6, ...b6, ...d6 and ...e6 setup with Rd1 and b3. Source: Symmetrical, Hedgehog, Flexible Formation.", self::HEDGEHOG_SOURCE, self::HEDGEHOG_FACTS, [
                ['c4', 'Move your pawn from c2 to c4.', 'This is the English Opening. The pawn controls d5 from the side and leaves your centre pawns free to decide later where they belong.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Black opens a path for the bishop on f8 and prepares ...d5, which would challenge your c4 pawn.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight towards the centre. It controls d4 and e5 and clears the way for castling.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Black mirrors your first move with the c-pawn, which is why this is called the Symmetrical English. The pawn on c5 controls d4, the square your d-pawn would like to reach.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'Develop the second knight. It controls d5 and e4, and together with your c4 pawn it makes ...d5 harder for Black to achieve.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops a knight, controls d5 and e4 and prepares to castle.'],
                ['g3', 'Move your pawn from g2 to g3.', 'Prepare to fianchetto your bishop on g2. From there it will look down the long diagonal towards d5, c6, b7 and a8.'],
                ['b6', 'Move your pawn from b7 to b6.', 'Black prepares to fianchetto the bishop on c8 as well. The pawn on b6 also supports the pawn on c5.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'Complete your fianchetto. Your knight on f3 stands in front of the bishop for now; once it moves, the diagonal opens all the way to a8.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Black puts the bishop on the same long diagonal from the other end. It attacks your knight on f3, which your bishop on g2 and the e2 pawn defend. The two bishops face each other with your knight in between.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle. Your king is sheltered by the pawns and the bishop on g2, and the rook comes to f1.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop to a modest square that suits this setup. From e7 it supports the c5 pawn and prepares castling.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Open the centre with the d-pawn. It attacks the pawn on c5, and your knight on f3 and queen defend it. If Black exchanges, you recapture and gain central space.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Black exchanges the c-pawn for your d-pawn. The pawn on d4 also attacks your knight on c3, so you should recapture straight away.'],
                ['Qxd4', 'Move your queen from d1 to d4, capturing the black pawn.', 'Recapture with the queen. Taking with the knight would clear the diagonal from b7 to your bishop on g2 and let Black exchange it with ...Bxg2. On d4 your queen is centralised and attacks the pawns on b6 and d7 and the knight on f6, all of which are defended.'],
                ['d6', 'Move your pawn from d7 to d6.', 'The Hedgehog takes shape: Black keeps the pawns close to home, on b6, d6 and e6. The pawn on d6 controls c5 and e5, so your pieces cannot easily settle there.'],
                ['Rd1', 'Move your rook from f1 to d1.', 'Put the rook on the d-file behind your queen, lined up against the pawn on d6. If Black ever frees the position with ...d5, the rook already stands on the file that opens.'],
                ['a6', 'Move your pawn from a7 to a6.', "Black completes the Hedgehog formation with pawns on a6, b6, d6 and e6. The pawn on a6 prepares ...b5, one of Black's two typical pawn breaks along with ...d5."],
                ['b3', 'Move your pawn from b2 to b3.', 'Support your c4 pawn with a pawn, so a later ...b5 meets more resistance. The move also frees b2 for your bishop on c1.'],
                ['Nbd7', 'Move your knight from b8 to d7.', "Black develops the last minor piece. From d7 the knight defends b6 and the knight on f6, and it can later jump to c5 or e5. The named Flexible Formation ends here, with Black's king still in the centre: your next tasks are to develop your bishop on c1, connect your rooks and keep Black's ...b5 and ...d5 breaks under control."],
            ]],
        ];

        if (false !== $this->connection->fetchOne("SELECT id FROM lesson WHERE id='english'")) {
            throw new \RuntimeException('The English Opening course already exists.');
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('English Opening teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }
        $start = $this->connection->fetchOne('SELECT id FROM chess_position WHERE position_key=?', [self::HEDGEHOG_FACTS[0]['fromKey']]);
        if (false === $start) {
            throw new \RuntimeException('The imported English Opening start position is missing.');
        }

        $this->addSql("INSERT INTO lesson (id,current_revision,sort_order) VALUES ('english',NULL,22)");
        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) VALUES ('english',1,?,?,?,UTC_TIMESTAMP(),'w')", ['English Opening', $introduction, (int) $start]);
        foreach ($lines as $index => [$lineId, $title, $description, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('english',1,?,?,?,?,?)", [$lineId, $title, $description, $index, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('english',1,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved English Opening edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=1 WHERE id='english'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve English Opening sessions and progress.');
    }
}
