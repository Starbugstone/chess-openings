<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Benoni Defense revision 2: keeps both revision 1 lines and adds two complete named pinned paths. */
final class Version20261016030300 extends AbstractMigration
{
    public const ARGENTINE_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nf3 c5 4. d5 exd5 5. cxd5 d6 6. Nc3 g6 7. e4 Bg7 8. Be2 O-O 9. O-O a6 10. a4 Bg4';
    public const HASTINGS_SOURCE = '1. d4 Nf6 2. c4 e6 3. g3 c5 4. d5 exd5 5. cxd5 d6 6. Nc3 g6 7. Bg2 Bg7 8. Nf3 O-O 9. O-O a6 10. a4 Nbd7 11. Nd2 Re8';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['classical-tal-line' => [20, 0], 'four-pawns-main-line' => [20, 1]];

    public const ARGENTINE_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2pP4/2P5/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2pP4/2P5/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pp4/2P5/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pp4/2P5/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pP4/8/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pP4/8/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/2N2N2/PP2PPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/2N2N2/PP2PPPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N2N2/PP2PPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N2N2/PP2PPPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/4P3/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/4P3/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQK2R b KQkq -', 'toKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'a2a4', 'san' => 'a4', 'fromKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/4P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/P3P3/2N2N2/1P2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'c8g4', 'san' => 'Bg4', 'fromKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/P3P3/2N2N2/1P2BPPP/R1BQ1RK1 b - -', 'toKey' => 'rn1q1rk1/1p3pbp/p2p1np1/2pP4/P3P1b1/2N2N2/1P2BPPP/R1BQ1RK1 w - -'],
    ];

    public const HASTINGS_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/6P1/PP2PP1P/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2PP4/6P1/PP2PP1P/RNBQKBNR w KQkq -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2p5/2PP4/6P1/PP2PP1P/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2pP4/2P5/6P1/PP2PP1P/RNBQKBNR b KQkq -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/4pn2/2pP4/2P5/6P1/PP2PP1P/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pp4/2P5/6P1/PP2PP1P/RNBQKBNR w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pp4/2P5/6P1/PP2PP1P/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pP4/8/6P1/PP2PP1P/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqkb1r/pp1p1ppp/5n2/2pP4/8/6P1/PP2PP1P/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/6P1/PP2PP1P/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/6P1/PP2PP1P/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/2N3P1/PP2PP1P/R1BQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pp3ppp/3p1n2/2pP4/8/2N3P1/PP2PP1P/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N3P1/PP2PP1P/R1BQKBNR w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N3P1/PP2PP1P/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N3P1/PP2PPBP/R1BQK1NR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pp3p1p/3p1np1/2pP4/8/2N3P1/PP2PPBP/R1BQK1NR b KQkq -', 'toKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/8/2N3P1/PP2PPBP/R1BQK1NR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/8/2N3P1/PP2PPBP/R1BQK1NR w KQkq -', 'toKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQK2R b KQkq -', 'toKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQ1RK1 b - -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'rnbq1rk1/pp3pbp/3p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQ1RK1 b - -', 'toKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQ1RK1 w - -'],
        ['uci' => 'a2a4', 'san' => 'a4', 'fromKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/8/2N2NP1/PP2PPBP/R1BQ1RK1 w - -', 'toKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/P7/2N2NP1/1P2PPBP/R1BQ1RK1 b - -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/1p3pbp/p2p1np1/2pP4/P7/2N2NP1/1P2PPBP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/1p1n1pbp/p2p1np1/2pP4/P7/2N2NP1/1P2PPBP/R1BQ1RK1 w - -'],
        ['uci' => 'f3d2', 'san' => 'Nd2', 'fromKey' => 'r1bq1rk1/1p1n1pbp/p2p1np1/2pP4/P7/2N2NP1/1P2PPBP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/1p1n1pbp/p2p1np1/2pP4/P7/2N3P1/1P1NPPBP/R1BQ1RK1 b - -'],
        ['uci' => 'f8e8', 'san' => 'Re8', 'fromKey' => 'r1bq1rk1/1p1n1pbp/p2p1np1/2pP4/P7/2N3P1/1P1NPPBP/R1BQ1RK1 b - -', 'toKey' => 'r1bqr1k1/1p1n1pbp/p2p1np1/2pP4/P7/2N3P1/1P1NPPBP/R1BQ1RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Benoni Defense revision 2: add complete named lines (classical-argentine, fianchetto-hastings)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. d4 and 2. c4. You challenge d4 with ...c5, and when White pushes past with d5, you exchange your e-pawn on d5. This gives the Benoni structure: White has more central space, while you get three pawns against two on the queenside, a half-open e-file and a bishop on the long diagonal. In the Classical Tal Line, White develops with Nf3, Be2 and castling, and you press on e4 with ...Re8 before ...Nbd7. Against the Four Pawns Attack with f4, you castle quickly and guard e5 with ...Nbd7 and ...Re8. The Argentine Counterattack reaches the Classical setup via 2...e6 and adds ...a6 and ...Bg4. Against the Fianchetto Variation you play ...a6, ...Nbd7 and ...Re8. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['classical-argentine', 'Classical Variation via 2...e6: ...a6 and ...Bg4', 'Ten Black moves. Reach the Classical Tal Line position through 2...e6 and 3...c5, then play ...a6 for queenside space and develop the bishop to g4 to challenge the knight on f3. Source: Classical Variation, Argentine Counterattack.', 2, self::ARGENTINE_SOURCE, self::ARGENTINE_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight to f6, where it controls e4 and d5. White cannot play e2-e4 yet without losing the pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Play ...e6 first and keep your options open. It frees your bishop on f8, and you can steer towards the Benoni with ...c5.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight to f3. While it stands there, the f2 pawn cannot advance, so White has given up the f2-f4 plans of the Four Pawns Attack for now.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Now strike at d4 with your c-pawn. Your bishop on f8 already defends c5, and if White advances d4-d5 you will reach the Benoni structure by exchanging on d5.'],
                ['d5', 'Move your pawn from d4 to d5.', 'White advances and gains space. The d5 pawn is attacked by your e6 pawn and your knight, and defended by the c4 pawn and the queen.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', 'Exchange on d5. Your pawn now attacks c4, which nothing defends, so White recaptures at once, and the e-file becomes half-open for you.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White recaptures with the c-pawn. This is the Benoni structure: White has more pawns in the centre, you have more on the queenside.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Put your d-pawn on d6. It blocks the d5 pawn, defends c5 and controls e5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight and defends d5 a second time, alongside the queen.'],
                ['g6', 'Move your pawn from g7 to g6.', 'Prepare the fianchetto. On g7 your bishop will aim along the long diagonal towards the queenside once the knight on f6 moves.'],
                ['e4', 'Move your pawn from e2 to e4.', 'White builds the big centre with pawns on d5 and e4. This is the same position as in the Classical Tal Line after 7. Nf3, reached by a different move order.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete the fianchetto. Once your knight on f6 moves, the bishop will look down the long diagonal through e5 and d4 towards the knight on c3. Your king can now castle.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop and prepares to castle. From e2 it also covers b5, a square you will want for your queenside pawns.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle. Your king is safe behind the fianchetto, and the rook comes to f8, from where it can move to the half-open e-file.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles too. Both kings are safe; White looks for the e4-e5 break, while you prepare play on the e-file and the queenside.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Instead of ...Re8, as in the Tal Line, prepare ...b5 to use your extra queenside pawn. The bishop on e2 attacks a6, but your b7 pawn, knight on b8 and rook on a8 defend it.'],
                ['a4', 'Move your pawn from a2 to a4.', 'White stops ...b5: the pawn on a4 controls b5. The cost is the b4 square: White\'s a-pawn has gone past it and the c-pawn has been exchanged, so your knight may later find a post there.'],
                ['Bg4', 'Move your bishop from c8 to g4.', 'Develop your light-squared bishop and attack the knight on f3, which the bishop on e2 and the g2 pawn defend. You are ready to exchange the bishop for that knight, which could otherwise reroute via d2 to c4 and press on your d6 pawn, as White began to do with Nd2 in the Tal Line. The named Argentine Counterattack ends here: your next tasks are to develop the knight on b8, decide whether to take on f3 and bring your rook to e8.'],
            ]],
            ['fianchetto-hastings', 'Fianchetto Variation: ...a6, ...Nbd7 and ...Re8 against g3', 'Eleven Black moves. Against White\'s kingside fianchetto, reach the Benoni structure via 2...e6, castle, play ...a6, develop the knight to d7 and put your rook on the e-file. Source: Fianchetto Variation, Hastings Defense, Main Line.', 3, self::HASTINGS_SOURCE, self::HASTINGS_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight to f6, where it controls e4 and d5. White cannot play e2-e4 yet without losing the pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Play ...e6 first and keep your options open. It frees your bishop on f8, and you can steer towards the Benoni with ...c5.'],
                ['g3', 'Move your pawn from g2 to g3.', 'White prepares to fianchetto the bishop on g2, from where it will support d5 and e4 along the long diagonal.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Strike at d4 with your c-pawn, which your bishop on f8 defends. If White advances d4-d5, you reach the Benoni structure.'],
                ['d5', 'Move your pawn from d4 to d5.', 'White advances and gains space. The d5 pawn is attacked by your e6 pawn and knight, and defended by the c4 pawn and the queen.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', 'Exchange on d5 and half-open the e-file for your rook. Your pawn attacks c4, which nothing defends, so White recaptures at once.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White recaptures with the c-pawn. This is the Benoni structure again: more white pawns in the centre, more black pawns on the queenside.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Put your d-pawn on d6, where it blocks the d5 pawn, defends c5 and controls e5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight and defends d5 a second time.'],
                ['g6', 'Move your pawn from g7 to g6.', 'Prepare to fianchetto your own bishop on g7, as White is about to do on g2.'],
                ['Bg2', 'Move your bishop from f1 to g2.', 'White completes the fianchetto. The bishop defends d5 along the long diagonal and will help shield White\'s king after castling.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete your own fianchetto. Your bishop shelters your king and will press along the long diagonal once the knight on f6 moves. Castling comes next.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the kingside knight and prepares to castle. For now the knight stands between the bishop on g2 and the d5 pawn.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle into safety. Your rook on f8 can later move to the half-open e-file.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles behind the fianchetto. Your plans are the same as in other Benoni lines: queenside expansion with ...a6 and ...b5, and pressure along the e-file.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Prepare ...b5 to use your extra queenside pawn. The pawn on a6 will support b5.'],
                ['a4', 'Move your pawn from a2 to a4.', 'White stops ...b5 with the a-pawn, which now controls b5. As in the Argentine line, this weakens b4, which White\'s a- and c-pawns can no longer guard.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight to d7. On c6 it would be attacked by the d5 pawn, while on d7 it defends c5 and can later jump to e5.'],
                ['Nd2', 'Move your knight from f3 to d2.', 'White reroutes the knight. The long diagonal opens again, so the bishop on g2 defends d5 once more, and the knight can head for c4, where it would attack your d6 pawn.'],
                ['Re8', 'Move your rook from f8 to e8.', 'Put your rook on the half-open e-file. It attacks the e2 pawn, which the knight on c3 and the queen defend. The named Hastings Defense Main Line ends here: your next tasks are to fight for b5 with moves such as ...Rb8, and to use the e5 square, which White\'s knight no longer controls, for your own knight.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='benoni'")) {
            throw new \RuntimeException('Benoni Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='benoni' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Benoni Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Benoni Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='benoni' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='benoni' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='benoni' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('benoni',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('benoni',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Benoni Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='benoni'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Benoni Defense sessions and progress.');
    }
}
