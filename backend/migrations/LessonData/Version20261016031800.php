<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Catalan revision 2: keep both current lines and add two complete named Closed Catalan lines. */
final class Version20261016031800 extends AbstractMigration
{
    public const ZAGORYANSKY_SOURCE = '1. d4 e6 2. c4 Nf6 3. Nf3 d5 4. g3 Be7 5. Bg2 O-O 6. O-O Nbd7 7. Qc2 c6 8. Rd1 b6 9. a4';
    public const SPASSKY_SOURCE = '1. d4 Nf6 2. c4 e6 3. g3 Be7 4. Bg2 d5 5. Nf3 O-O 6. O-O Nbd7 7. Qc2 c6 8. b3 b6 9. Rd1 Bb7 10. Nc3 b5';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['closed-main-line' => [23, 0], 'closed-sokolsky' => [20, 1]];

    public const ZAGORYANSKY_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R b KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R w KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 b - -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 w - -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 b - -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 b - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 w - -'],
        ['uci' => 'f1d1', 'san' => 'Rd1', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 w - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNBR2K1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNBR2K1 b - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNBR2K1 w - -'],
        ['uci' => 'a2a4', 'san' => 'a4', 'fromKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNBR2K1 w - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/P1PP4/5NP1/1PQ1PPBP/RNBR2K1 b - -'],
    ];

    public const SPASSKY_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppppbppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'rnbqk2r/ppppbppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppppbppp/4pn2/8/2PP4/6P1/PP2PPBP/RNBQK1NR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqk2r/ppppbppp/4pn2/8/2PP4/6P1/PP2PPBP/RNBQK1NR b KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/6P1/PP2PPBP/RNBQK1NR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/6P1/PP2PPBP/RNBQK1NR w KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 b - -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 w - -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PP2PPBP/RNBQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 b - -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'r1bq1rk1/pppnbppp/4pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 b - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 w - -'],
        ['uci' => 'b2b3', 'san' => 'b3', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQ1PPBP/RNB2RK1 w - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNB2RK1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNB2RK1 b - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNB2RK1 w - -'],
        ['uci' => 'f1d1', 'san' => 'Rd1', 'fromKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNB2RK1 w - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNBR2K1 b - -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNBR2K1 b - -', 'toKey' => 'r2q1rk1/pb1nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNBR2K1 w - -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r2q1rk1/pb1nbppp/1pp1pn2/3p4/2PP4/1P3NP1/P1Q1PPBP/RNBR2K1 w - -', 'toKey' => 'r2q1rk1/pb1nbppp/1pp1pn2/3p4/2PP4/1PN2NP1/P1Q1PPBP/R1BR2K1 b - -'],
        ['uci' => 'b6b5', 'san' => 'b5', 'fromKey' => 'r2q1rk1/pb1nbppp/1pp1pn2/3p4/2PP4/1PN2NP1/P1Q1PPBP/R1BR2K1 b - -', 'toKey' => 'r2q1rk1/pb1nbppp/2p1pn2/1p1p4/2PP4/1PN2NP1/P1Q1PPBP/R1BR2K1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Catalan revision 2: add the Closed Zagoryansky Variation and Spassky Gambit lines';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = "Learn the Catalan as White: a Queen's Gambit centre with pawns on c4 and d4, combined with a kingside fianchetto that puts your bishop on the long diagonal towards d5, b7 and a8. All four lines are complete named Closed Catalan records, in which Black keeps the pawn on d5 and builds a solid setup with ...c6 and ...b6. In the Main Line you protect c4 with Qc2, develop with Bf4 and break with e4. In the Sokolsky you support c4 with b3 and play Bb2. In the Zagoryansky you put a rook on d1 and gain queenside space with a4. In the Spassky Gambit you combine b3, Rd1 and Nc3, and Black offers a pawn with ...b5. Black replies automatically along the selected line; real opponents can choose other continuations.";
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['closed-zagoryansky', 'Zagoryansky Variation: Qc2, Rd1 and a4', 'Nine White moves. Fianchetto after Nf3, protect c4 with Qc2, put a rook behind your d-pawn and meet ...b6 with a4. Source: Closed, Zagoryansky Variation.', 2, self::ZAGORYANSKY_SOURCE, self::ZAGORYANSKY_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'Take space in the centre and open a path for the bishop on c1.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Black opens a path for the bishop on f8 and prepares ...d5.'],
                ['c4', 'Move your pawn from c2 to c4.', "Gain more central space. The pawn controls d5, and together with d4 it gives you the Queen's Gambit centre that the Catalan is built on."],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops a knight and controls e4. The position is the same as after 1. d4 Nf6 2. c4 e6, reached by another move order.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight, guard d4, control e5 and clear the way for castling kingside.'],
                ['d5', 'Move your pawn from d7 to d5.', "Black takes central space. The pawn on d5 attacks your c4 pawn, and the structure is now the same as in the Queen's Gambit Declined."],
                ['g3', 'Move your pawn from g2 to g3.', 'This is the Catalan move. You prepare Bg2, placing the bishop on the long diagonal that runs through d5 towards b7 and a8. Your c4 pawn is not protected for the moment; in this line Black leaves it alone.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop and prepares to castle, keeping the pawn on d5 instead of capturing on c4. Lines in which Black holds d5 like this are called the Closed Catalan.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'Complete the fianchetto. Your knight on f3 stands in front of the bishop for now, but once the knight moves and the centre opens, the bishop presses along the whole diagonal through e4 and d5 to b7 and a8.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black puts the king in safety before deciding how to develop the queenside pieces.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle. Your king is sheltered by the pawns and the bishop on g2, and the rook comes to f1.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Black develops this knight to d7 rather than c6, so the c-pawn stays free to support d5 with ...c6 or to strike with ...c5. From d7 the knight also watches e5 and c5.'],
                ['Qc2', 'Move your queen from d1 to c2.', 'Protect your c4 pawn with the queen, so ...dxc4 can be met by recapturing. From c2 the queen also supports a later e2-e4, and it leaves d1 free for a rook.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Black reinforces d5 with a pawn, forming a solid c6-d5-e6 structure. The aim is to keep the centre closed in front of your g2 bishop while finishing development.'],
                ['Rd1', 'Move your rook from f1 to d1.', "Put the rook on d1, behind your d-pawn and on the same file as Black's queen. Whenever the d-file opens, for example after an e4 advance and exchanges in the centre, the rook is already in place."],
                ['b6', 'Move your pawn from b7 to b6.', 'The pawn on e6 blocks the natural diagonal of the bishop on c8, so Black prepares to develop it on the queenside, on b7 or a6.'],
                ['a4', 'Move your pawn from a2 to a4.', 'This is the Zagoryansky Variation. The pawn gains queenside space and controls b5, which makes ...b5 harder for Black. It can advance to a5 to challenge the b6 pawn and open the a-file for your rook. The named Zagoryansky Variation ends here: your next tasks are to develop your bishop on c1 and knight on b1, then choose between the a5 advance and the central break e4.'],
            ]],
            ['closed-spassky-gambit', 'Spassky Gambit: b3, Rd1, Nc3 and ...b5', "Ten White moves. Fianchetto early, protect c4 with Qc2 and b3, put a rook on d1 and develop the knight to c3, then face Black's ...b5 pawn offer. Source: Closed, Spassky Gambit.", 3, self::SPASSKY_SOURCE, self::SPASSKY_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'Take space in the centre and open a path for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops a knight and controls e4, keeping the choice of pawn structure open for another move.'],
                ['c4', 'Move your pawn from c2 to c4.', "Gain more central space. The pawn controls d5, and together with d4 it gives you the Queen's Gambit centre that the Catalan is built on."],
                ['e6', 'Move your pawn from e7 to e6.', 'Black opens a path for the bishop on f8 and prepares ...d5, aiming for a solid centre.'],
                ['g3', 'Move your pawn from g2 to g3.', 'Play the Catalan move before Nf3. The pawn prepares Bg2, which puts the bishop on the long diagonal towards d5, b7 and a8.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop and prepares to castle before committing the d-pawn. From e7 the bishop also supports the knight on f6.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'Complete the fianchetto. With no pawn on d5 yet and no knight on f3, the bishop already attacks the pawn on b7 down the long diagonal; the bishop on c8 defends it.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Black takes central space and blocks the long diagonal: the pawn on d5 now stands between your bishop and b7. It also attacks your c4 pawn.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop the knight, guard d4, control e5 and clear the way for castling. The knight now stands in front of your bishop, but it can step away later to reopen the diagonal.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black puts the king in safety before deciding how to develop the queenside pieces.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle. Your king is sheltered by the pawns and the bishop on g2, and the rook comes to f1.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Black develops this knight to d7 rather than c6, so the c-pawn stays free to support d5 with ...c6 or to strike with ...c5. From d7 the knight also watches e5 and c5.'],
                ['Qc2', 'Move your queen from d1 to c2.', 'Protect your c4 pawn with the queen, so ...dxc4 can be met by recapturing. From c2 the queen also supports a later e2-e4.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Black reinforces d5 with a pawn, forming a solid c6-d5-e6 structure. The aim is to keep the centre closed in front of your g2 bishop while finishing development.'],
                ['b3', 'Move your pawn from b2 to b3.', 'Support c4 with a pawn as well. If Black ever captures on c4, you can recapture with the b-pawn and keep a pawn in the centre. The move also frees b2 for your bishop on c1.'],
                ['b6', 'Move your pawn from b7 to b6.', 'The pawn on e6 blocks the natural diagonal of the bishop on c8, so Black prepares to develop it on b7.'],
                ['Rd1', 'Move your rook from f1 to d1.', 'Put the rook behind your d-pawn, where it also defends d4. When the centre opens, for example after an e4 advance, the rook already stands on the right file.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Black completes the queenside fianchetto. For now the bishop sits behind the pawns on c6 and d5, but if the centre opens it will contest the long diagonal against your g2 bishop.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'Develop your last knight. On c3 it adds pressure on d5 and supports a later e4. Note that it now blocks your queen from c4, so the pawn on b3 is the only defender of c4.'],
                ['b5', 'Move your pawn from b6 to b5.', 'This is the Spassky Gambit. Black pushes the b-pawn again and hits c4 a second time, so your c4 pawn is attacked by two pawns and defended only by b3. The b5 pawn is defended only by c6 while your c4 pawn and knight attack it, so Black is offering a pawn for open lines. The named Spassky Gambit ends here: your next tasks are to settle the queenside tension, by taking on b5 or by keeping c4 protected, and to watch the c-file, where your queen stands.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='catalan'")) {
            throw new \RuntimeException('Catalan Opening revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='catalan' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Catalan Opening revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Catalan Opening teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='catalan' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='catalan' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='catalan' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('catalan',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('catalan',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Catalan Opening edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='catalan'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Catalan Opening sessions and progress.');
    }
}
