<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Tarrasch Defense revision 2: keep both Classical lines and add the complete Réti and Stoltz Variation records. */
final class Version20261016030500 extends AbstractMigration
{
    public const RETI_SOURCE = '1. d4 d5 2. c4 e6 3. Nc3 c5 4. cxd5 exd5 5. Nf3 Nc6 6. g3 Nf6 7. Bg2 Be7 8. O-O O-O 9. dxc5 Bxc5 10. Na4';
    public const STOLTZ_SOURCE = '1. d4 d5 2. c4 e6 3. Nf3 c5 4. cxd5 exd5 5. g3 Nc6 6. Bg2 Nf6 7. O-O Be7 8. Nc3 O-O 9. Bg5 Be6 10. Rc1 b6';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['classical-main-line' => [22, 0], 'classical-advance' => [18, 1]];

    public const RETI_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/4p3/2pp4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkbnr/pp3ppp/4p3/2pp4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/4p3/2pP4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnbqkbnr/pp3ppp/4p3/2pP4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/2N2NP1/PP2PP1P/R1BQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/2N2NP1/PP2PP1P/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/2N2NP1/PP2PP1P/R1BQKB1R w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/2N2NP1/PP2PP1P/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQK2R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQK2R b KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQK2R w KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -', 'toKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 w - -'],
        ['uci' => 'd4c5', 'san' => 'dxc5', 'fromKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pp2bppp/2n2n2/2Pp4/8/2N2NP1/PP2PPBP/R1BQ1RK1 b - -'],
        ['uci' => 'e7c5', 'san' => 'Bxc5', 'fromKey' => 'r1bq1rk1/pp2bppp/2n2n2/2Pp4/8/2N2NP1/PP2PPBP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n2n2/2bp4/8/2N2NP1/PP2PPBP/R1BQ1RK1 w - -'],
        ['uci' => 'c3a4', 'san' => 'Na4', 'fromKey' => 'r1bq1rk1/pp3ppp/2n2n2/2bp4/8/2N2NP1/PP2PPBP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n2n2/2bp4/N7/5NP1/PP2PPBP/R1BQ1RK1 b - -'],
    ];

    public const STOLTZ_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/4p3/2pp4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkbnr/pp3ppp/4p3/2pp4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/4p3/2pP4/3P4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnbqkbnr/pp3ppp/4p3/2pP4/3P4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/5NP1/PP2PP1P/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pp3ppp/8/2pp4/3P4/5NP1/PP2PP1P/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/5NP1/PP2PP1P/RNBQKB1R w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/5NP1/PP2PP1P/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/5NP1/PP2PPBP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pp3ppp/2n5/2pp4/3P4/5NP1/PP2PPBP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQ1RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/pp3ppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQ1RK1 w kq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/5NP1/PP2PPBP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 b kq -', 'toKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 w - -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp4/3P4/2N2NP1/PP2PPBP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp2B1/3P4/2N2NP1/PP2PPBP/R2Q1RK1 b - -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1bq1rk1/pp2bppp/2n2n2/2pp2B1/3P4/2N2NP1/PP2PPBP/R2Q1RK1 b - -', 'toKey' => 'r2q1rk1/pp2bppp/2n1bn2/2pp2B1/3P4/2N2NP1/PP2PPBP/R2Q1RK1 w - -'],
        ['uci' => 'a1c1', 'san' => 'Rc1', 'fromKey' => 'r2q1rk1/pp2bppp/2n1bn2/2pp2B1/3P4/2N2NP1/PP2PPBP/R2Q1RK1 w - -', 'toKey' => 'r2q1rk1/pp2bppp/2n1bn2/2pp2B1/3P4/2N2NP1/PP2PPBP/2RQ1RK1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r2q1rk1/pp2bppp/2n1bn2/2pp2B1/3P4/2N2NP1/PP2PPBP/2RQ1RK1 b - -', 'toKey' => 'r2q1rk1/p3bppp/1pn1bn2/2pp2B1/3P4/2N2NP1/PP2PPBP/2RQ1RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Tarrasch Defense revision 2: add the Classical Réti Variation and Stoltz Variation lines';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. d4 and 2. c4 with ...d5, ...e6 and ...c5. Instead of only holding d5, you strike at d4 at once and accept that your d-pawn may become isolated; in return your pieces develop freely on open lines. In all four lines White fianchettoes the bishop on g2 to aim at d5. The Main Line teaches you to exchange on d4 and play with an isolated pawn on d5 and active pieces. The Advance Variation teaches you to push ...c4 instead and gain queenside space. In the Réti Variation White takes on c5 first and you recapture with the bishop. In the Stoltz Variation you reach the Classical position through 3. Nf3, then guard d5 with ...Be6 and support c5 with ...b6. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['reti-dxc5', 'Classical Réti: White takes on c5 and chases your bishop', 'Nine Black moves. Develop and castle as in the Main Line; when White takes on c5 instead of playing Bg5, recapture with the bishop, accepting an isolated d-pawn in return for active pieces, then see how White chases your bishop with Na4. Source: Classical Variation, Réti Variation.', 2, self::RETI_SOURCE, self::RETI_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Occupy the centre with your d-pawn. It controls e4 and c4, so White cannot easily advance the e-pawn two squares.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen\'s Gambit.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Decline the pawn and support d5 with the e-pawn. This opens the diagonal for your bishop on f8, but for now it shuts in your bishop on c8.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops a knight and adds another attacker to d5.'],
                ['c5', 'Move your pawn from c7 to c5.', 'This is the Tarrasch Defense. Strike at d4 with the c-pawn and fight for the centre actively. You accept that your d-pawn may become isolated in return for free development for your pieces.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White exchanges on d5 at once. Your recapture decides the pawn structure for the rest of the opening.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', 'Recapture with the e-pawn. This opens the diagonal for your bishop on c8, and your queen on d8 defends the pawn on d5. Your pawn on c5 still attacks d4.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the second knight, defends d4 and controls e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and add a second attacker to d4, together with the pawn on c5.'],
                ['g3', 'Move your pawn from g2 to g3.', 'White prepares to fianchetto the bishop on g2. From there it will aim along the long diagonal at d5 once the knight on f3 moves.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the kingside knight. It defends d5, controls e4 and clears g8 on the way to castling.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'White completes the fianchetto. The bishop stands on the long diagonal behind the knight on f3, pointing towards d5.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop to e7, where it protects the knight on f6, and prepare to castle.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles, putting the king behind the fianchettoed bishop.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle and bring your king to safety. The rook comes to f8, and the bishop on c8 is now your only undeveloped minor piece.'],
                ['dxc5', 'Move your pawn from d4 to c5, capturing the black pawn.', 'Here the Réti Variation takes its own path: instead of developing the bishop to g5, White resolves the tension by taking on c5. Your d5 pawn is now isolated, and the white pawn on c5 is attacked by your bishop on e7 with nothing defending it.'],
                ['Bxc5', 'Move your bishop from e7 to c5, capturing the white pawn.', 'Recapture with the bishop, which lands on an active square. It aims at f2 next to White\'s king, and the f2 pawn is pinned against the king. Material is level.'],
                ['Na4', 'Move your knight from c3 to a4.', 'White attacks your bishop with the knight. Nothing defends the bishop, so you must save it, and with the knight gone from c3 your d5 pawn is now attacked only by the queen. The named Réti Variation ends here: your next tasks are to put the bishop on a safe square, develop your bishop on c8 and bring your rooks to the centre, keeping d5 well protected.'],
            ]],
            ['stoltz-b6', 'Stoltz Variation: 3. Nf3, then ...Be6 and ...b6', 'Ten Black moves. Meet 3. Nf3 with the same Tarrasch set-up and reach the position of the other lines by another move order, then keep the tension on d4, guard d5 with ...Be6 and support c5 with ...b6, so that dxc5 can be met with a pawn recapture. Source: Queen\'s Gambit Declined: Tarrasch Defense, Stoltz Variation.', 3, self::STOLTZ_SOURCE, self::STOLTZ_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Occupy the centre with your d-pawn. It controls e4 and c4, so White cannot easily advance the e-pawn two squares.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen\'s Gambit.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Decline the pawn and support d5 with the e-pawn. This opens the diagonal for your bishop on f8, but for now it shuts in your bishop on c8.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Here this line takes its own path: White develops the kingside knight first instead of playing Nc3. It defends d4 and controls e5.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Play the Tarrasch move anyway: strike at d4 with your c-pawn, ready to accept an isolated d-pawn in return for free development.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White captures on d5. The pawn on d5 attacks your e6 pawn but has no defender for the moment, while your e6 pawn and queen both attack it.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', 'Recapture with the e-pawn. This opens the diagonal of your bishop on c8, and your c5 pawn still attacks d4.'],
                ['g3', 'Move your pawn from g2 to g3.', 'White prepares to put the bishop on g2, where it will aim along the long diagonal at your d5 pawn.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight to its natural square, adding a second attacker to d4.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'White completes the fianchetto. The knight on f3 blocks the bishop\'s diagonal for now.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop your other knight. It defends d5 and clears g8 for castling.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles, keeping the knight on b1 at home for one more move.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop. From e7 it protects your c5 pawn and the knight on f6, and your king is ready to castle.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the queenside knight, which attacks d5. The position is now exactly the one the other lines of this course reach after 8. O-O; only the move order was different.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle and bring your king to safety. The rook comes to f8, and the bishop on c8 is now your only undeveloped minor piece.'],
                ['Bg5', 'Move your bishop from c1 to g5.', 'White develops the bishop and attacks your knight on f6, the piece that defends d5 together with your queen.'],
                ['Be6', 'Move your bishop from c8 to e6.', 'Instead of exchanging on d4 as in the Main Line or pushing ...c4 as in the Advance Variation, develop the bishop to e6. It adds a third defender to d5, alongside your queen and knight, before White gathers more pieces against it.'],
                ['Rc1', 'Move your rook from a1 to c1.', 'White puts the rook on the c-file, facing your c5 pawn with the knight on c3 in between. If White later takes on c5, the rook is already on that file.'],
                ['b6', 'Move your pawn from b7 to b6.', 'Support c5 with the b-pawn. If White now takes on c5, you can recapture with the pawn and get two pawns side by side on c5 and d5, called hanging pawns, instead of an isolated d-pawn. The named Stoltz Variation ends here: your next tasks are to bring a rook to c8 or d8 and keep your central pawns protected, choosing the moment to advance them or to exchange on d4.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='tarrasch'")) {
            throw new \RuntimeException('Tarrasch Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='tarrasch' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Tarrasch Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Tarrasch Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='tarrasch' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='tarrasch' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='tarrasch' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('tarrasch',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('tarrasch',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Tarrasch Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='tarrasch'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Tarrasch Defense sessions and progress.');
    }
}
