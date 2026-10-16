<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** King's Indian Defense revision 2: keep the Panno, correct one Classical main line note (7...Nc6) and add complete named pinned paths for White's other main systems. */
final class Version20261016032000 extends AbstractMigration
{
    public const CLASSICAL_SOURCE = '1. d4 Nf6 2. c4 g6 3. Nc3 Bg7 4. e4 d6 5. Nf3 O-O 6. Be2 e5 7. O-O Nc6 8. d5 Ne7 9. Ne1 Nd7 10. Be3 f5 11. f3 f4 12. Bf2 g5 13. Rc1 Ng6 14. c5';
    public const BAYONET_SOURCE = '1. d4 Nf6 2. c4 g6 3. Nc3 Bg7 4. e4 d6 5. Nf3 O-O 6. Be2 e5 7. O-O Nc6 8. d5 Ne7 9. b4 Nh5 10. Re1';
    public const PETROSIAN_SOURCE = '1. d4 Nf6 2. c4 g6 3. Nc3 Bg7 4. e4 d6 5. Nf3 O-O 6. Be2 e5 7. d5 Nbd7 8. Bg5 h6 9. Bh4 g5 10. Bg3 Nh5 11. h4';
    public const POSITIONAL_SOURCE = '1. d4 Nf6 2. c4 g6 3. Nc3 Bg7 4. e4 d6 5. Nf3 O-O 6. Be2 e5 7. O-O Nbd7 8. Re1 c6 9. Bf1 a5';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['fianchetto-panno' => [26, 1]];

    public const CLASSICAL_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/ppp2pbp/2np1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'r1bq1rk1/ppp2pbp/2np1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/ppp2pbp/2np1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'c6e7', 'san' => 'Ne7', 'fromKey' => 'r1bq1rk1/ppp2pbp/2np1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'f3e1', 'san' => 'Ne1', 'fromKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N5/PP2BPPP/R1BQNRK1 b - -'],
        ['uci' => 'f6d7', 'san' => 'Nd7', 'fromKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N5/PP2BPPP/R1BQNRK1 b - -', 'toKey' => 'r1bq1rk1/pppnnpbp/3p2p1/3Pp3/2P1P3/2N5/PP2BPPP/R1BQNRK1 w - -'],
        ['uci' => 'c1e3', 'san' => 'Be3', 'fromKey' => 'r1bq1rk1/pppnnpbp/3p2p1/3Pp3/2P1P3/2N5/PP2BPPP/R1BQNRK1 w - -', 'toKey' => 'r1bq1rk1/pppnnpbp/3p2p1/3Pp3/2P1P3/2N1B3/PP2BPPP/R2QNRK1 b - -'],
        ['uci' => 'f7f5', 'san' => 'f5', 'fromKey' => 'r1bq1rk1/pppnnpbp/3p2p1/3Pp3/2P1P3/2N1B3/PP2BPPP/R2QNRK1 b - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Ppp2/2P1P3/2N1B3/PP2BPPP/R2QNRK1 w - -'],
        ['uci' => 'f2f3', 'san' => 'f3', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Ppp2/2P1P3/2N1B3/PP2BPPP/R2QNRK1 w - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Ppp2/2P1P3/2N1BP2/PP2B1PP/R2QNRK1 b - -'],
        ['uci' => 'f5f4', 'san' => 'f4', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Ppp2/2P1P3/2N1BP2/PP2B1PP/R2QNRK1 b - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Pp3/2P1Pp2/2N1BP2/PP2B1PP/R2QNRK1 w - -'],
        ['uci' => 'e3f2', 'san' => 'Bf2', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Pp3/2P1Pp2/2N1BP2/PP2B1PP/R2QNRK1 w - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Pp3/2P1Pp2/2N2P2/PP2BBPP/R2QNRK1 b - -'],
        ['uci' => 'g6g5', 'san' => 'g5', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p2p1/3Pp3/2P1Pp2/2N2P2/PP2BBPP/R2QNRK1 b - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p4/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/R2QNRK1 w - -'],
        ['uci' => 'a1c1', 'san' => 'Rc1', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p4/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/R2QNRK1 w - -', 'toKey' => 'r1bq1rk1/pppnn1bp/3p4/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/2RQNRK1 b - -'],
        ['uci' => 'e7g6', 'san' => 'Ng6', 'fromKey' => 'r1bq1rk1/pppnn1bp/3p4/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/2RQNRK1 b - -', 'toKey' => 'r1bq1rk1/pppn2bp/3p2n1/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/2RQNRK1 w - -'],
        ['uci' => 'c4c5', 'san' => 'c5', 'fromKey' => 'r1bq1rk1/pppn2bp/3p2n1/3Pp1p1/2P1Pp2/2N2P2/PP2BBPP/2RQNRK1 w - -', 'toKey' => 'r1bq1rk1/pppn2bp/3p2n1/2PPp1p1/4Pp2/2N2P2/PP2BBPP/2RQNRK1 b - -'],
    ];

    public const BAYONET_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/ppp2pbp/2np1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'r1bq1rk1/ppp2pbp/2np1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/ppp2pbp/2np1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'c6e7', 'san' => 'Ne7', 'fromKey' => 'r1bq1rk1/ppp2pbp/2np1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'b2b4', 'san' => 'b4', 'fromKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/1PP1P3/2N2N2/P3BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'f6h5', 'san' => 'Nh5', 'fromKey' => 'r1bq1rk1/ppp1npbp/3p1np1/3Pp3/1PP1P3/2N2N2/P3BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p2p1/3Pp2n/1PP1P3/2N2N2/P3BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'f1e1', 'san' => 'Re1', 'fromKey' => 'r1bq1rk1/ppp1npbp/3p2p1/3Pp2n/1PP1P3/2N2N2/P3BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/ppp1npbp/3p2p1/3Pp2n/1PP1P3/2N2N2/P3BPPP/R1BQR1K1 b - -'],
    ];

    public const PETROSIAN_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQK2R b KQ -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQK2R b KQ -', 'toKey' => 'r1bq1rk1/pppn1pbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'r1bq1rk1/pppn1pbp/3p1np1/3Pp3/2P1P3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'r1bq1rk1/pppn1pbp/3p1np1/3Pp1B1/2P1P3/2N2N2/PP2BPPP/R2QK2R b KQ -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'r1bq1rk1/pppn1pbp/3p1np1/3Pp1B1/2P1P3/2N2N2/PP2BPPP/R2QK2R b KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p1npp/3Pp1B1/2P1P3/2N2N2/PP2BPPP/R2QK2R w KQ -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'r1bq1rk1/pppn1pb1/3p1npp/3Pp1B1/2P1P3/2N2N2/PP2BPPP/R2QK2R w KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p1npp/3Pp3/2P1P2B/2N2N2/PP2BPPP/R2QK2R b KQ -'],
        ['uci' => 'g6g5', 'san' => 'g5', 'fromKey' => 'r1bq1rk1/pppn1pb1/3p1npp/3Pp3/2P1P2B/2N2N2/PP2BPPP/R2QK2R b KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p1n1p/3Pp1p1/2P1P2B/2N2N2/PP2BPPP/R2QK2R w KQ -'],
        ['uci' => 'h4g3', 'san' => 'Bg3', 'fromKey' => 'r1bq1rk1/pppn1pb1/3p1n1p/3Pp1p1/2P1P2B/2N2N2/PP2BPPP/R2QK2R w KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p1n1p/3Pp1p1/2P1P3/2N2NB1/PP2BPPP/R2QK2R b KQ -'],
        ['uci' => 'f6h5', 'san' => 'Nh5', 'fromKey' => 'r1bq1rk1/pppn1pb1/3p1n1p/3Pp1p1/2P1P3/2N2NB1/PP2BPPP/R2QK2R b KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p3p/3Pp1pn/2P1P3/2N2NB1/PP2BPPP/R2QK2R w KQ -'],
        ['uci' => 'h2h4', 'san' => 'h4', 'fromKey' => 'r1bq1rk1/pppn1pb1/3p3p/3Pp1pn/2P1P3/2N2NB1/PP2BPPP/R2QK2R w KQ -', 'toKey' => 'r1bq1rk1/pppn1pb1/3p3p/3Pp1pn/2P1P2P/2N2NB1/PP2BPP1/R2QK2R b KQ -'],
    ];

    public const POSITIONAL_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g7g6', 'san' => 'g6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8g7', 'san' => 'Bg7', 'fromKey' => 'rnbqkb1r/pppppp1p/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqk2r/ppppppbp/5np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N5/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP3PPP/R1BQKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbq1rk1/ppp1ppbp/3p1np1/8/2PPP3/2N2N2/PP2BPPP/R1BQK2R b KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/ppp2pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pppn1pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -'],
        ['uci' => 'f1e1', 'san' => 'Re1', 'fromKey' => 'r1bq1rk1/pppn1pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pppn1pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQR1K1 b - -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'r1bq1rk1/pppn1pbp/3p1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQR1K1 b - -', 'toKey' => 'r1bq1rk1/pp1n1pbp/2pp1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQR1K1 w - -'],
        ['uci' => 'e2f1', 'san' => 'Bf1', 'fromKey' => 'r1bq1rk1/pp1n1pbp/2pp1np1/4p3/2PPP3/2N2N2/PP2BPPP/R1BQR1K1 w - -', 'toKey' => 'r1bq1rk1/pp1n1pbp/2pp1np1/4p3/2PPP3/2N2N2/PP3PPP/R1BQRBK1 b - -'],
        ['uci' => 'a7a5', 'san' => 'a5', 'fromKey' => 'r1bq1rk1/pp1n1pbp/2pp1np1/4p3/2PPP3/2N2N2/PP3PPP/R1BQRBK1 b - -', 'toKey' => 'r1bq1rk1/1p1n1pbp/2pp1np1/p3p3/2PPP3/2N2N2/PP3PPP/R1BQRBK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish King\'s Indian Defense revision 2: correct the classical-main-line Nc6 note, add bayonet-sokolov, petrosian-keres, positional-defense';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = "Play Black against 1. d4 and 2. c4. In the King's Indian Defense you let White build a broad pawn centre, fianchetto your bishop on g7, castle and only then challenge the centre. Learn five complete named lines: the Classical main line, a race between your kingside storm and White's c4-c5; the Fianchetto Panno, with queenside play by ...a6, ...Rb8 and ...b5; the Bayonet Attack, where ...Nh5 answers b2-b4; the Petrosian, where ...h6 and ...g5 break a pin; and the Positional Defense, keeping the tension with ...Nbd7, ...c6 and ...a5. White replies automatically along the selected line; real opponents can choose other continuations.";
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['classical-main-line', 'Classical main line: kingside storm against queenside break', 'Thirteen Black moves. Fianchetto, castle and strike with ...e5, reroute your knights after White closes the centre, then push ...f5, ...f4 and ...g5 while White breaks with c4-c5. Source: Orthodox Variation, Classical System, Kozul Gambit.', 0, self::CLASSICAL_SOURCE, self::CLASSICAL_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', "Develop the knight to a square where it controls e4 and d5. You do not occupy the centre with a pawn yet: this flexible, hypermodern approach is the basis of the King's Indian."],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['g6', 'Move your pawn from g7 to g6.', "Prepare to fianchetto your bishop on g7. From there it will work along the long diagonal towards White's centre and queenside."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete the fianchetto. Once the knight on f6 moves, the bishop will look straight down the long diagonal towards d4. Your king can now castle quickly.'],
                ['e4', 'Move your pawn from e2 to e4.', "White builds a big pawn centre on c4, d4 and e4. In the King's Indian you allow this, planning to challenge the centre once your king is safe."],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White cannot simply push e4-e5 to chase your knight. The pawn also prepares ...e5 and opens a path for the bishop on c8.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the kingside knight, supports d4 and controls e5.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle into the safety of the fianchetto. Your king sits behind the pawns and the bishop on g7, and the rook comes to f8, behind the f-pawn you will push later.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop modestly and prepares to castle.'],
                ['e5', 'Move your pawn from e7 to e5.', "Challenge White's centre with a pawn of your own. Your pawn on d6 supports it, and White must choose between exchanging on e5, advancing with d5 or keeping the tension."],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and keeps the central tension for now.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and add a second attacker to d4, alongside your pawn on e5. White usually settles the tension in the centre now.'],
                ['d5', 'Move your pawn from d4 to d5.', "White closes the centre and attacks your knight. The pawn chains now point at opposite wings: White's pawns on e4 and d5 point towards the queenside, where White will play c4-c5; your pawns on e5 and d6 point towards the kingside, where you will attack."],
                ['Ne7', 'Move your knight from c6 to e7.', 'Retreat the knight towards the kingside, where your play will be. From e7 it supports a later ...f5 and can continue to g6.'],
                ['Ne1', 'Move your knight from f3 to e1.', 'White reroutes the knight. It can go to d3 to support c4-c5, and leaving f3 makes room for f2-f3, which will reinforce e4.'],
                ['Nd7', 'Move your knight from f6 to d7.', 'Move the knight off f6 so your f-pawn can advance to f5. On d7 it also watches c5, the square White needs for the queenside break.'],
                ['Be3', 'Move your bishop from c1 to e3.', 'White develops the bishop to a square where it also controls c5, supporting the c4-c5 advance.'],
                ['f5', 'Move your pawn from f7 to f5.', 'This is your main pawn break. The pawn attacks e4 and begins your kingside play, with the rook on f8 standing behind it.'],
                ['f3', 'Move your pawn from f2 to f3.', 'White supports e4 with a pawn, so the centre stays closed.'],
                ['f4', 'Move your pawn from f5 to f4.', 'Advance with gain of time: the pawn attacks the bishop on e3. You release the pressure on e4, but gain kingside space and a pawn wedge for ...g5 and ...g4.'],
                ['Bf2', 'Move your bishop from e3 to f2.', "White retreats the bishop. From f2 it still controls c5 and stays close to White's king."],
                ['g5', 'Move your pawn from g6 to g5.', "Start the pawn storm. The g-pawn supports f4 and prepares ...g4 to attack f3 and open lines towards White's king."],
                ['Rc1', 'Move your rook from a1 to c1.', 'White puts the rook on the c-file, ready for the moment the file opens after c4-c5.'],
                ['Ng6', 'Move your knight from e7 to g6.', "Bring the knight into the kingside attack. On g6 it supports the pawn on f4 and controls h4, joining your pawns in the storm against White's king."],
                ['c5', 'Move your pawn from c4 to c5.', "White makes the queenside break. The pawn attacks d6 and aims to open the c-file for the rook on c1; White is ready to give up this pawn to open lines quickly. The named Kozul Gambit ends here. The middlegame is a race: your next task is to keep the kingside storm moving with ...h5 and ...g4 while making sure White's queenside play does not arrive first."],
            ]],
            ['bayonet-sokolov', 'Bayonet Attack: ...Nh5 against White\'s b4', 'Nine Black moves. Reach the closed Classical centre with ...Nc6 and ...Ne7, then meet White\'s early b2-b4 by moving your knight to h5 to prepare ...f5. Source: Orthodox Variation, Bayonet Attack, Sokolov\'s Line.', 2, self::BAYONET_SOURCE, self::BAYONET_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', "Develop the knight to a square where it controls e4 and d5. You do not occupy the centre with a pawn yet: this flexible, hypermodern approach is the basis of the King's Indian."],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['g6', 'Move your pawn from g7 to g6.', "Prepare to fianchetto your bishop on g7. From there it will work along the long diagonal towards White's centre and queenside."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete the fianchetto. Once the knight on f6 moves, the bishop will look straight down the long diagonal towards d4. Your king can now castle quickly.'],
                ['e4', 'Move your pawn from e2 to e4.', "White builds a big pawn centre on c4, d4 and e4. In the King's Indian you allow this, planning to challenge the centre once your king is safe."],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White cannot simply push e4-e5 to chase your knight. The pawn also prepares ...e5 and opens a path for the bishop on c8.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the kingside knight, supports d4 and controls e5.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle into the safety of the fianchetto. Your king sits behind the pawns and the bishop on g7, and the rook comes to f8, behind the f-pawn you will push later.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop modestly and prepares to castle.'],
                ['e5', 'Move your pawn from e7 to e5.', "Challenge White's centre with a pawn of your own. Your pawn on d6 supports it, and White must choose between exchanging on e5, advancing with d5 or keeping the tension."],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and keeps the central tension for now.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and add a second attacker to d4, alongside your pawn on e5. White usually settles the tension in the centre now.'],
                ['d5', 'Move your pawn from d4 to d5.', "White closes the centre and attacks your knight. The pawn chains now point at opposite wings: White's pawns on e4 and d5 point towards the queenside, where White will play c4-c5; your pawns on e5 and d6 point towards the kingside, where you will attack."],
                ['Ne7', 'Move your knight from c6 to e7.', 'Retreat the knight towards the kingside, where your play will be. From e7 it supports a later ...f5 and can continue to g6.'],
                ['b4', 'Move your pawn from b2 to b4.', 'This is the Bayonet Attack. Instead of rerouting the knight with Ne1, White expands on the queenside at once: the pawn on b4 gains space and will support c4-c5, the break that attacks your pawn on d6.'],
                ['Nh5', 'Move your knight from f6 to h5.', 'Move the knight off f6 so your f-pawn can advance to f5; White is already pushing on the queenside, so your own play must start quickly. From h5 the knight eyes f4, and with f6 cleared your bishop on g7 now helps the pawn on d6 defend e5.'],
                ['Re1', 'Move your rook from f1 to e1.', 'White puts the rook on the e-file, behind the bishop on e2. Once the bishop moves, the rook will defend e4 against your coming ...f5, and f1 is now free as a retreat square for the bishop. The named Sokolov\'s Line ends here: your next tasks are to push ...f5 to challenge e4 and to look for a good moment to bring the knight to f4, while White presses on with c4-c5.'],
            ]],
            ['petrosian-keres', 'Petrosian Variation: ...h6, ...g5 and ...Nh5 against the pin', 'Ten Black moves. White closes the centre at once with d5 and pins your knight with Bg5; break the pin with ...h6 and ...g5, then hit the bishop with ...Nh5. Source: Petrosian Variation, Keres Defense.', 3, self::PETROSIAN_SOURCE, self::PETROSIAN_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', "Develop the knight to a square where it controls e4 and d5. You do not occupy the centre with a pawn yet: this flexible, hypermodern approach is the basis of the King's Indian."],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['g6', 'Move your pawn from g7 to g6.', "Prepare to fianchetto your bishop on g7. From there it will work along the long diagonal towards White's centre and queenside."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete the fianchetto. Once the knight on f6 moves, the bishop will look straight down the long diagonal towards d4. Your king can now castle quickly.'],
                ['e4', 'Move your pawn from e2 to e4.', "White builds a big pawn centre on c4, d4 and e4. In the King's Indian you allow this, planning to challenge the centre once your king is safe."],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White cannot simply push e4-e5 to chase your knight. The pawn also prepares ...e5 and opens a path for the bishop on c8.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the kingside knight, supports d4 and controls e5.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle into the safety of the fianchetto. Your king sits behind the pawns and the bishop on g7, and the rook comes to f8, behind the f-pawn you will push later.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop modestly and prepares to castle.'],
                ['e5', 'Move your pawn from e7 to e5.', "Challenge White's centre with a pawn of your own. Your pawn on d6 supports it, and White must choose between exchanging on e5, advancing with d5 or keeping the tension."],
                ['d5', 'Move your pawn from d4 to d5.', 'This is the Petrosian Variation. White closes the centre at once, before castling, and gains space. The pawn on d5 takes the c6 and e6 squares away from your pieces, and the bishop on c1 can still come to g5.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'With c6 controlled by the pawn on d5, develop the knight to d7. There it defends e5 and your knight on f6, and later it can jump to c5 to attack e4.'],
                ['Bg5', 'Move your bishop from c1 to g5.', 'White pins your knight on f6 to your queen on d8: if the knight moved, the bishop would take the queen. The pin holds back your usual plan of moving the knight and pushing ...f5.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Attack the bishop with your pawn. The knight on f3 defends it, but giving a bishop for a pawn is no bargain, so White must decide at once: retreat the bishop or exchange it for your knight on f6.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'White retreats along the same diagonal and keeps the pin: your knight on f6 still cannot move without exposing your queen.'],
                ['g5', 'Move your pawn from g6 to g5.', 'Break the pin: your g-pawn now stands between the bishop and the knight on f6, and it attacks the bishop, supported by the pawn on h6. The cost is that your kingside pawns have moved forward and no longer control f5 and h5, squares White may try to use later.'],
                ['Bg3', 'Move your bishop from h4 to g3.', 'White retreats the bishop to g3, where it attacks your pawn on e5 together with the knight on f3. Your knight on d7 and pawn on d6 defend it, so e5 is attacked twice and defended twice.'],
                ['Nh5', 'Move your knight from f6 to h5.', 'The knight is no longer pinned. On h5 it attacks the bishop on g3, threatening ...Nxg3, which would leave you with both bishops against White\'s one. Leaving f6 also clears the way for ...f5, your bishop on g7 now gives e5 a third defender and your queen now defends g5.'],
                ['h4', 'Move your pawn from h2 to h4.', 'White strikes back: the h-pawn attacks your pawn on g5 and aims to open the h-file. White has not castled, so the rook on h1 already stands behind it. The advance also leaves the bishop on g3 with one defender, the pawn on f2. The named Keres Defense ends here: your next tasks are to answer the attack on g5, keep the h-file closed and choose the moment for ...Nxg3 or ...f5.'],
            ]],
            ['positional-defense', 'Positional Defense: ...Nbd7, ...c6 and ...a5', 'Nine Black moves. Keep the central tension with ...Nbd7 instead of ...Nc6, control d5 with ...c6 and gain queenside space with ...a5. Source: Orthodox Variation, Positional Defense, Main Line.', 4, self::POSITIONAL_SOURCE, self::POSITIONAL_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', "Develop the knight to a square where it controls e4 and d5. You do not occupy the centre with a pawn yet: this flexible, hypermodern approach is the basis of the King's Indian."],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['g6', 'Move your pawn from g7 to g6.', "Prepare to fianchetto your bishop on g7. From there it will work along the long diagonal towards White's centre and queenside."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bg7', 'Move your bishop from f8 to g7.', 'Complete the fianchetto. Once the knight on f6 moves, the bishop will look straight down the long diagonal towards d4. Your king can now castle quickly.'],
                ['e4', 'Move your pawn from e2 to e4.', "White builds a big pawn centre on c4, d4 and e4. In the King's Indian you allow this, planning to challenge the centre once your king is safe."],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White cannot simply push e4-e5 to chase your knight. The pawn also prepares ...e5 and opens a path for the bishop on c8.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the kingside knight, supports d4 and controls e5.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle into the safety of the fianchetto. Your king sits behind the pawns and the bishop on g7, and the rook comes to f8, behind the f-pawn you will push later.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop modestly and prepares to castle.'],
                ['e5', 'Move your pawn from e7 to e5.', "Challenge White's centre with a pawn of your own. Your pawn on d6 supports it, and White must choose between exchanging on e5, advancing with d5 or keeping the tension."],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and keeps the central tension for now.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'This is the Positional Defense. Instead of ...Nc6, develop the knight to d7, where it defends e5 and leaves your c-pawn free to advance. You keep the tension in the centre and let White decide whether to push d4-d5 or exchange on e5.'],
                ['Re1', 'Move your rook from f1 to e1.', 'White puts the rook on the e-file, behind the bishop on e2. It prepares Bf1, after which the rook will defend e4 directly, and the central tension stays.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Take control of d5 and b5 with your c-pawn, so the knight on c3 cannot settle on either square. The move also opens the diagonal from d8 to a5, so your queen can later come to b6 or a5.'],
                ['Bf1', 'Move your bishop from e2 to f1.', 'White retreats the bishop to f1. The rook on e1 now defends e4 directly, and from f1 the bishop still protects c4 and g2. White keeps a solid centre and waits for you to show your plan.'],
                ['a5', 'Move your pawn from a7 to a5.', 'Gain space on the queenside. Your pawn on a5 controls b4, so White cannot easily expand with b2-b4 as in the Bayonet Attack, and a knight arriving on c5 would be hard to chase away with a b-pawn. The named Positional Defense Main Line ends here: your next tasks are to keep e5 well protected, decide whether to release the tension with ...exd4, and then use the e-file and the c5 square to put pressure on e4.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='kings-indian'")) {
            throw new \RuntimeException('King\'s Indian Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='kings-indian' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained King\'s Indian Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('King\'s Indian Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='kings-indian' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='kings-indian' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='kings-indian' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('kings-indian',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('kings-indian',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved King\'s Indian Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='kings-indian'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous King\'s Indian Defense sessions and progress.');
    }
}
