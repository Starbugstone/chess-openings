<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Alekhine Defense revision 2: keep both current lines and add the complete Four Pawns Attack, Korchnoi Variation record. */
final class Version20261016030700 extends AbstractMigration
{
    public const KORCHNOI_SOURCE = '1. e4 Nf6 2. e5 Nd5 3. d4 d6 4. c4 Nb6 5. f4 dxe5 6. fxe5 Bf5 7. Nc3 e6 8. Nf3 Be7 9. Be2 O-O 10. O-O f6';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['four-pawns-tartakower' => [22, 0], 'exchange-karpov' => [21, 1]];

    public const KORCHNOI_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/4P3/8/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'f6d5', 'san' => 'Nd5', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/4P3/8/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/8/3nP3/8/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkb1r/pppppppp/8/3nP3/8/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/8/3nP3/3P4/8/PPP2PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqkb1r/pppppppp/8/3nP3/3P4/8/PPP2PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/3p4/3nP3/3P4/8/PPP2PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/ppp1pppp/3p4/3nP3/3P4/8/PPP2PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/3p4/3nP3/2PP4/8/PP3PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd5b6', 'san' => 'Nb6', 'fromKey' => 'rnbqkb1r/ppp1pppp/3p4/3nP3/2PP4/8/PP3PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/1n1p4/4P3/2PP4/8/PP3PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'rnbqkb1r/ppp1pppp/1n1p4/4P3/2PP4/8/PP3PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/1n1p4/4P3/2PP1P2/8/PP4PP/RNBQKBNR b KQkq -'],
        ['uci' => 'd6e5', 'san' => 'dxe5', 'fromKey' => 'rnbqkb1r/ppp1pppp/1n1p4/4P3/2PP1P2/8/PP4PP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/1n6/4p3/2PP1P2/8/PP4PP/RNBQKBNR w KQkq -'],
        ['uci' => 'f4e5', 'san' => 'fxe5', 'fromKey' => 'rnbqkb1r/ppp1pppp/1n6/4p3/2PP1P2/8/PP4PP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp1pppp/1n6/4P3/2PP4/8/PP4PP/RNBQKBNR b KQkq -'],
        ['uci' => 'c8f5', 'san' => 'Bf5', 'fromKey' => 'rnbqkb1r/ppp1pppp/1n6/4P3/2PP4/8/PP4PP/RNBQKBNR b KQkq -', 'toKey' => 'rn1qkb1r/ppp1pppp/1n6/4Pb2/2PP4/8/PP4PP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rn1qkb1r/ppp1pppp/1n6/4Pb2/2PP4/8/PP4PP/RNBQKBNR w KQkq -', 'toKey' => 'rn1qkb1r/ppp1pppp/1n6/4Pb2/2PP4/2N5/PP4PP/R1BQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rn1qkb1r/ppp1pppp/1n6/4Pb2/2PP4/2N5/PP4PP/R1BQKBNR b KQkq -', 'toKey' => 'rn1qkb1r/ppp2ppp/1n2p3/4Pb2/2PP4/2N5/PP4PP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rn1qkb1r/ppp2ppp/1n2p3/4Pb2/2PP4/2N5/PP4PP/R1BQKBNR w KQkq -', 'toKey' => 'rn1qkb1r/ppp2ppp/1n2p3/4Pb2/2PP4/2N2N2/PP4PP/R1BQKB1R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rn1qkb1r/ppp2ppp/1n2p3/4Pb2/2PP4/2N2N2/PP4PP/R1BQKB1R b KQkq -', 'toKey' => 'rn1qk2r/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP4PP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rn1qk2r/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP4PP/R1BQKB1R w KQkq -', 'toKey' => 'rn1qk2r/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rn1qk2r/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQK2R b KQkq -', 'toKey' => 'rn1q1rk1/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rn1q1rk1/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQK2R w KQ -', 'toKey' => 'rn1q1rk1/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQ1RK1 b - -'],
        ['uci' => 'f7f6', 'san' => 'f6', 'fromKey' => 'rn1q1rk1/ppp1bppp/1n2p3/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQ1RK1 b - -', 'toKey' => 'rn1q1rk1/ppp1b1pp/1n2pp2/4Pb2/2PP4/2N2N2/PP2B1PP/R1BQ1RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Alekhine Defense revision 2: add the Four Pawns Attack, Korchnoi Variation line';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. e4 with ...Nf6. Your knight attacks the e4 pawn at once and invites White\'s pawns forward; once they have advanced, you attack them with your pieces and pawns, especially the pawn on d4. In all three lines White builds a large centre with d4 and c4 and chases your knight to b6. The Four Pawns Attack, Tartakower Variation teaches you to exchange on e5, press d4 with your knight and queen, and castle queenside. The Exchange Variation, Karpov Variation teaches you to recapture with ...cxd6, fianchetto your bishop against d4 and castle kingside. The Four Pawns Attack, Korchnoi Variation teaches you to play ...Bf5 first, castle kingside and strike at e5 with ...f6. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['four-pawns-korchnoi', 'Four Pawns Attack, Korchnoi: kingside castling and ...f6', 'Ten Black moves. Exchange on e5 as in the Tartakower line, then bring the bishop to f5 before ...e6 instead of playing ...Nc6, castle kingside and strike at White\'s e5 pawn with ...f6. Source: Four Pawns Attack, Korchnoi Variation.', 2, self::KORCHNOI_SOURCE, self::KORCHNOI_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Alekhine Defense is a reply to this first move.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight and attack the pawn on e4 at once. This is the Alekhine Defense: you invite White\'s pawns forward and plan to attack them later with your pieces and pawns.'],
                ['e5', 'Move your pawn from e4 to e5.', 'White advances the pawn with gain of time. It attacks your knight on f6 and takes space.'],
                ['Nd5', 'Move your knight from f6 to d5.', 'Move the knight to the centre. On d5 no white pawn attacks it yet. White can chase it with c4, but each pawn advance leaves squares behind that you can use later.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White builds a broad centre. The pawn on d4 protects e5 and opens a diagonal for the bishop on c1.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Challenge the pawn on e5 at once. Your d-pawn attacks it and opens a diagonal for your bishop on c8.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White chases your knight again and takes more space. White now has three pawns side by side on c4, d4 and e5.'],
                ['Nb6', 'Move your knight from d5 to b6.', 'Retreat the knight to b6, where your pawns on a7 and c7 protect it. From there it keeps an eye on c4 and d5.'],
                ['f4', 'Move your pawn from f2 to f4.', 'This is the Four Pawns Attack: this line follows the Tartakower line until your sixth move. White supports e5 with the f-pawn and claims as much space as possible. The price is that the advanced pawns need constant protection, and White has used five moves on pawns without developing a piece.'],
                ['dxe5', 'Move your pawn from d6 to e5, capturing the white pawn.', 'Exchange on e5. Your queen on d8 now looks down the half-open d-file at the pawn on d4, and White has to decide how to recapture.'],
                ['fxe5', 'Move your pawn from f4 to e5, capturing the black pawn.', 'White recaptures with the f-pawn and keeps a pawn on e5. Only the queen on d1 defends the pawn on d4, which your queen attacks.'],
                ['Bf5', 'Move your bishop from c8 to f5.', 'Here the Korchnoi Variation takes its own path: instead of ...Nc6, develop your light-squared bishop before ...e6 shuts it in. From f5 it controls e4 and d3, two squares that no white pawn guards any more. Your queen\'s knight waits on b8, and your king will castle on the kingside.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, which controls d5 and e4, the square your bishop is watching.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Your bishop is already outside the pawn chain, so ...e6 no longer shuts it in. It opens the diagonal of the bishop on f8, protects the bishop on f5 and covers the d5 square.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the second knight, which defends both d4 and e5.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop and prepare to castle. On e7 it will also support the ...f6 advance that comes later in this line.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop and prepares to castle. From e2 it still defends the pawn on c4 against your knight on b6.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle on the kingside. Your rook now stands on f8, behind the f-pawn, ready to support its advance.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles too, so both kings are on the kingside. White\'s rook on f1 stands on the file the f-pawn has left.'],
                ['f6', 'Move your pawn from f7 to f6.', 'Strike at e5, the front of White\'s centre. Your rook on f8, the bishop on e7 and the g7 pawn all support f6. If White takes on f6, recapturing with the bishop puts it on the diagonal towards d4. The named Korchnoi Variation ends here: your next tasks are to develop the knight on b8 and to keep up the pressure on e5 and d4.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='alekhine'")) {
            throw new \RuntimeException('Alekhine Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='alekhine' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Alekhine Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Alekhine Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='alekhine' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='alekhine' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='alekhine' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('alekhine',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('alekhine',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Alekhine Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='alekhine'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Alekhine Defense sessions and progress.');
    }
}
