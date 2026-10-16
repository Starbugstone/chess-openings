<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Ruy Lopez revision 2: keep the three Closed lines and add a fourth Closed set-up, two Open Variation lines and the Berlin Wall. */
final class Version20261016031400 extends AbstractMigration
{
    public const ZAITSEV_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Be7 6. Re1 b5 7. Bb3 d6 8. c3 O-O 9. h3 Bb7 10. d4 Re8 11. Nbd2 Bf8 12. a3 h6';
    public const BRESLAU_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Nxe4 6. d4 b5 7. Bb3 d5 8. dxe5 Be6 9. c3 Be7 10. Re1 O-O 11. Nd4 Nxe5';
    public const HOWELL_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bb5 a6 4. Ba4 Nf6 5. O-O Nxe4 6. d4 b5 7. Bb3 d5 8. dxe5 Be6 9. Qe2 Be7 10. Rd1 O-O 11. c4 bxc4 12. Bxc4 Qd7';
    public const BERLIN_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bb5 Nf6 4. O-O Nxe4 5. d4 Nd6 6. Bxc6 dxc6 7. dxe5 Nf5 8. Qxd8+ Kxd8 9. Nc3 Bd7';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['chigorin-main-line' => [28, 0], 'breyer-defense' => [24, 1], 'marshall-attack' => [36, 2]];

    public const ZAITSEV_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1b5', 'san' => 'Bb5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b5a4', 'san' => 'Ba4', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'f1e1', 'san' => 'Re1', 'fromKey' => 'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 b kq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqk2r/1pppbppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 b kq -', 'toKey' => 'r1bqk2r/2ppbppp/p1n2n2/1p2p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 w kq -'],
        ['uci' => 'a4b3', 'san' => 'Bb3', 'fromKey' => 'r1bqk2r/2ppbppp/p1n2n2/1p2p3/B3P3/5N2/PPPP1PPP/RNBQR1K1 w kq -', 'toKey' => 'r1bqk2r/2ppbppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 b kq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'r1bqk2r/2ppbppp/p1n2n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 b kq -', 'toKey' => 'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 w kq -'],
        ['uci' => 'c2c3', 'san' => 'c3', 'fromKey' => 'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/1B3N2/PPPP1PPP/RNBQR1K1 w kq -', 'toKey' => 'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 b kq -', 'toKey' => 'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 w - -'],
        ['uci' => 'h2h3', 'san' => 'h3', 'fromKey' => 'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N2/PP1P1PPP/RNBQR1K1 w - -', 'toKey' => 'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N1P/PP1P1PP1/RNBQR1K1 b - -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'r1bq1rk1/2p1bppp/p1np1n2/1p2p3/4P3/1BP2N1P/PP1P1PP1/RNBQR1K1 b - -', 'toKey' => 'r2q1rk1/1bp1bppp/p1np1n2/1p2p3/4P3/1BP2N1P/PP1P1PP1/RNBQR1K1 w - -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r2q1rk1/1bp1bppp/p1np1n2/1p2p3/4P3/1BP2N1P/PP1P1PP1/RNBQR1K1 w - -', 'toKey' => 'r2q1rk1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP3PP1/RNBQR1K1 b - -'],
        ['uci' => 'f8e8', 'san' => 'Re8', 'fromKey' => 'r2q1rk1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP3PP1/RNBQR1K1 b - -', 'toKey' => 'r2qr1k1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP3PP1/RNBQR1K1 w - -'],
        ['uci' => 'b1d2', 'san' => 'Nbd2', 'fromKey' => 'r2qr1k1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP3PP1/RNBQR1K1 w - -', 'toKey' => 'r2qr1k1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP1N1PP1/R1BQR1K1 b - -'],
        ['uci' => 'e7f8', 'san' => 'Bf8', 'fromKey' => 'r2qr1k1/1bp1bppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP1N1PP1/R1BQR1K1 b - -', 'toKey' => 'r2qrbk1/1bp2ppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP1N1PP1/R1BQR1K1 w - -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'r2qrbk1/1bp2ppp/p1np1n2/1p2p3/3PP3/1BP2N1P/PP1N1PP1/R1BQR1K1 w - -', 'toKey' => 'r2qrbk1/1bp2ppp/p1np1n2/1p2p3/3PP3/PBP2N1P/1P1N1PP1/R1BQR1K1 b - -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'r2qrbk1/1bp2ppp/p1np1n2/1p2p3/3PP3/PBP2N1P/1P1N1PP1/R1BQR1K1 b - -', 'toKey' => 'r2qrbk1/1bp2pp1/p1np1n1p/1p2p3/3PP3/PBP2N1P/1P1N1PP1/R1BQR1K1 w - -'],
    ];

    public const BRESLAU_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1b5', 'san' => 'Bb5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b5a4', 'san' => 'Ba4', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f6e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B3n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B3n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'a4b3', 'san' => 'Bb3', 'fromKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pp3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd4e5', 'san' => 'dxe5', 'fromKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pp3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'c2c3', 'san' => 'c3', 'fromKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQ1RK1 b kq -', 'toKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'f1e1', 'san' => 'Re1', 'fromKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQ1RK1 w kq -', 'toKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQR1K1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQR1K1 b kq -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQR1K1 w - -'],
        ['uci' => 'f3d4', 'san' => 'Nd4', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/4n3/1BP2N2/PP3PPP/RNBQR1K1 w - -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/3Nn3/1BP5/PP3PPP/RNBQR1K1 b - -'],
        ['uci' => 'c6e5', 'san' => 'Nxe5', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/3Nn3/1BP5/PP3PPP/RNBQR1K1 b - -', 'toKey' => 'r2q1rk1/2p1bppp/p3b3/1p1pn3/3Nn3/1BP5/PP3PPP/RNBQR1K1 w - -'],
    ];

    public const HOWELL_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1b5', 'san' => 'Bb5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b5a4', 'san' => 'Ba4', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/1ppp1ppp/p1n5/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f6e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n2n2/4p3/B3P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B3n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B3n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqkb1r/1ppp1ppp/p1n5/4p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'a4b3', 'san' => 'Bb3', 'fromKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/B2Pn3/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqkb1r/2pp1ppp/p1n5/1p2p3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pp3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd4e5', 'san' => 'dxe5', 'fromKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pp3/3Pn3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1bqkb1r/2p2ppp/p1n5/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd1e2', 'san' => 'Qe2', 'fromKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNB2RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r2qkb1r/2p2ppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNB2RK1 b kq -', 'toKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNB2RK1 w kq -'],
        ['uci' => 'f1d1', 'san' => 'Rd1', 'fromKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNB2RK1 w kq -', 'toKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNBR2K1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r2qk2r/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNBR2K1 b kq -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNBR2K1 w - -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/4n3/1B3N2/PPP1QPPP/RNBR2K1 w - -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/2P1n3/1B3N2/PP2QPPP/RNBR2K1 b - -'],
        ['uci' => 'b5c4', 'san' => 'bxc4', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/1p1pP3/2P1n3/1B3N2/PP2QPPP/RNBR2K1 b - -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/3pP3/2p1n3/1B3N2/PP2QPPP/RNBR2K1 w - -'],
        ['uci' => 'b3c4', 'san' => 'Bxc4', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/3pP3/2p1n3/1B3N2/PP2QPPP/RNBR2K1 w - -', 'toKey' => 'r2q1rk1/2p1bppp/p1n1b3/3pP3/2B1n3/5N2/PP2QPPP/RNBR2K1 b - -'],
        ['uci' => 'd8d7', 'san' => 'Qd7', 'fromKey' => 'r2q1rk1/2p1bppp/p1n1b3/3pP3/2B1n3/5N2/PP2QPPP/RNBR2K1 b - -', 'toKey' => 'r4rk1/2pqbppp/p1n1b3/3pP3/2B1n3/5N2/PP2QPPP/RNBR2K1 w - -'],
    ];

    public const BERLIN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1b5', 'san' => 'Bb5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f6e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/1B2p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n5/1B2p3/4n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n5/1B2p3/4n3/5N2/PPPP1PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n5/1B2p3/3Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'e4d6', 'san' => 'Nd6', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n5/1B2p3/3Pn3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2nn4/1B2p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'b5c6', 'san' => 'Bxc6', 'fromKey' => 'r1bqkb1r/pppp1ppp/2nn4/1B2p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2Bn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7c6', 'san' => 'dxc6', 'fromKey' => 'r1bqkb1r/pppp1ppp/2Bn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2pn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd4e5', 'san' => 'dxe5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2pn4/4p3/3P4/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2pn4/4P3/8/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd6f5', 'san' => 'Nf5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2pn4/4P3/8/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd1d8', 'san' => 'Qxd8+', 'fromKey' => 'r1bqkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bQkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 b kq -'],
        ['uci' => 'e8d8', 'san' => 'Kxd8', 'fromKey' => 'r1bQkb1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 b kq -', 'toKey' => 'r1bk1b1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 w - -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r1bk1b1r/ppp2ppp/2p5/4Pn2/8/5N2/PPP2PPP/RNB2RK1 w - -', 'toKey' => 'r1bk1b1r/ppp2ppp/2p5/4Pn2/8/2N2N2/PPP2PPP/R1B2RK1 b - -'],
        ['uci' => 'c8d7', 'san' => 'Bd7', 'fromKey' => 'r1bk1b1r/ppp2ppp/2p5/4Pn2/8/2N2N2/PPP2PPP/R1B2RK1 b - -', 'toKey' => 'r2k1b1r/pppb1ppp/2p5/4Pn2/8/2N2N2/PPP2PPP/R1B2RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Ruy Lopez revision 2: add the Smyslov-Breyer-Zaitsev Hybrid, Open Breslau, Open Howell Attack and Berlin Wall';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = "Learn the Ruy Lopez as White. After 1. e4 e5 2. Nf3 Nc6 3. Bb5 your bishop attacks the knight that defends e5, and that pressure on Black's centre shapes the whole game. Four lines are Closed Ruy Lopez lines with ...a6, ...Nf6, ...Be7 and ...b5: the Chigorin with ...Na5 and ...c5, the Breyer with the knight returning via b8 to d7, the Marshall Attack, where Black gives up a pawn to attack your king, and a set-up with ...Bb7, ...Re8 and ...Bf8. Two lines meet the Open Variation, where Black takes on e4: you win the pawn back, then play c3, Re1 and Nd4, or Qe2, Rd1 and c4. The last line is the Berlin Defense with an early queen exchange. Black replies automatically along the selected line; real opponents can choose other continuations.";
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['zaitsev-hybrid', 'Closed Ruy Lopez: ...Bb7, ...Re8 and ...Bf8 against your centre', 'Twelve White moves. Follow the Closed Ruy Lopez to h3, build the d4 centre and add Nbd2 and a3 while Black regroups with ...Bb7, ...Re8, ...Bf8 and ...h6 to put pressure on e4. Source: Closed, Smyslov-Breyer-Zaitsev Hybrid.', 3, self::ZAITSEV_SOURCE, self::ZAITSEV_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open paths for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing further.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight with a threat: it attacks the pawn on e5. It also clears the way for castling.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 with a knight. This knight is the target of your next move.'],
                ['Bb5', 'Move your bishop from f1 to b5.', "This is the Ruy Lopez. The bishop attacks the knight that defends e5. There is no immediate threat to win the pawn, but this pressure on Black's centre lasts for many moves."],
                ['a6', 'Move your pawn from a7 to a6.', 'Black asks your bishop to decide at once: take the knight, or retreat and keep the pressure.'],
                ['Ba4', 'Move your bishop from b5 to a4.', 'Keep the bishop and its pressure on the knight on c6. The bishop also keeps the option of retreating to b3 later.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops and attacks your pawn on e4, which nothing defends at the moment.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle rather than defend e4 directly. Your king is safe and the rook can come to e1. Black could take on e4 here, which leads to the Open Variation; this line follows the quieter ...Be7.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop and prepares to castle. This is the Closed Ruy Lopez: Black keeps the centre solid instead of taking on e4.'],
                ['Re1', 'Move your rook from f1 to e1.', 'Defend e4 with the rook. With e4 safe, capturing on c6 and then taking the e5 pawn with your knight becomes a real threat.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Black meets that threat by driving your bishop away from c6. The pawn also gains space on the queenside.'],
                ['Bb3', 'Move your bishop from a4 to b3.', 'Retreat to the a2-g8 diagonal. From b3 the bishop aims at d5 and f7 and stays active well into the middlegame.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Black supports e5 with a pawn, so the threat against it is gone. The move also opens a diagonal for the bishop on c8.'],
                ['c3', 'Move your pawn from c2 to c3.', 'Prepare d4. When the d-pawn arrives, it will be supported by this pawn and you keep a broad pawn centre. The move also gives your bishop a retreat square on c2.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles. Both kings are safe, and the middlegame plans begin.'],
                ['h3', 'Move your pawn from h2 to h3.', 'Before playing d4, stop ...Bg4. That move would pin your knight on f3 against the queen, and the knight is an important defender of d4. This quiet preparation is typical of the Closed Ruy Lopez.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Black develops the bishop to b7 straight away. It aims along the long diagonal at your pawn on e4, for now from behind the knight on c6. Unlike the Chigorin and Breyer lines, Black keeps the knight on c6 and builds pressure on your centre instead.'],
                ['d4', 'Move your pawn from d2 to d4.', "Build the classical centre. Your pawn on d4 attacks e5 and is defended by the c3 pawn, the knight on f3 and the queen. Black's e5 pawn holds, supported by the knight on c6 and the pawn on d6."],
                ['Re8', 'Move your rook from f8 to e8.', "Black puts the rook on the e-file, ready to support e5 once the bishop on e7 makes way. Note that the pawn on f7 is now defended only by Black's king, while your bishop on b3 aims at it."],
                ['Nbd2', 'Move your knight from b1 to d2.', "Develop your last knight to d2, since c3 holds a pawn. It adds a second defender to e4, alongside the rook, and can later continue to f1 and g3. For now it blocks your queen's defence of d4, but the c3 pawn and the knight on f3 still hold that pawn."],
                ['Bf8', 'Move your bishop from e7 to f8.', "Black retreats the bishop to f8, where it defends d6 and g7, and the rook on e8 now backs up the e5 pawn. Black's pieces aim at your centre: the knight on f6 attacks e4, and the bishop on b7 stands behind the knight on c6 on the same diagonal."],
                ['a3', 'Move your pawn from a2 to a3.', "A quiet move with two purposes. Your c3 pawn covers b4 for now, but if it later recaptures on d4, a3 still keeps Black's knight out of b4, where it would attack c2, your bishop's usual retreat square. It also frees a2 as a second retreat for the bishop on b3."],
                ['h6', 'Move your pawn from h7 to h6.', 'Black takes the g5 square from your pieces. A knight on g5 would attack f7 together with your bishop on b3, and a bishop on g5 would pin the knight on f6 to the queen. ...h6 is the move of the Smyslov Defense, added here to ...Bb7, ...Re8 and ...Bf8. The named hybrid line ends here: your next tasks are to continue the knight route via f1 to g3, develop the bishop on c1 and choose between keeping the central tension and playing d4-d5.'],
            ]],
            ['open-breslau', 'Open Variation: c3, Re1 and Nd4 against the knight on e4', 'Eleven White moves. When Black takes on e4, win the pawn back with d4 and dxe5, then play c3, Re1 and Nd4 against the Classical ...Be7 set-up until Black takes the e5 pawn. Source: Open, Breslau Variation.', 4, self::BRESLAU_SOURCE, self::BRESLAU_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open paths for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing further.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight with a threat: it attacks the pawn on e5. It also clears the way for castling.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 with a knight. This knight is the target of your next move.'],
                ['Bb5', 'Move your bishop from f1 to b5.', "This is the Ruy Lopez. The bishop attacks the knight that defends e5. There is no immediate threat to win the pawn, but this pressure on Black's centre lasts for many moves."],
                ['a6', 'Move your pawn from a7 to a6.', 'Black asks your bishop to decide at once: take the knight, or retreat and keep the pressure.'],
                ['Ba4', 'Move your bishop from b5 to a4.', 'Keep the bishop and its pressure on the knight on c6. The bishop also keeps the option of retreating to b3 later.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops and attacks your pawn on e4, which nothing defends at the moment.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle rather than defend e4 directly. Your king is safe and the rook can come to e1. In this line Black takes the pawn on e4, which is the Open Variation.'],
                ['Nxe4', 'Move your knight from f6 to e4, capturing the white pawn.', 'Black takes the pawn and is a pawn up for the moment. In the Open Variation the knight stands actively in the centre; your plan is to open lines at once and win the pawn back.'],
                ['d4', 'Move your pawn from d2 to d4.', "Strike in the centre while Black's king is still on e8. The pawn on e5 is now attacked by your pawn and knight and defended only by the knight on c6, which your bishop on a4 still attacks."],
                ['b5', 'Move your pawn from b7 to b5.', 'Black drives your bishop away from a4, so it no longer attacks the knight on c6, the only defender of e5.'],
                ['Bb3', 'Move your bishop from a4 to b3.', 'Retreat to the a2-g8 diagonal. From b3 the bishop attacks f7 and will press against d5, the square Black needs to support the knight on e4.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Black supports the knight on e4 with a pawn and opens a diagonal for the bishop on c8. In return Black leaves e5 to you: it is attacked by your pawn and knight and defended only by the knight on c6.'],
                ['dxe5', 'Move your pawn from d4 to e5, capturing the black pawn.', "Take on e5: material is level again. With your d-pawn gone, your queen and the bishop on b3 both attack the pawn on d5, which only Black's queen defends. Your e5 pawn also takes the d6 and f6 squares away from Black's pieces."],
                ['Be6', 'Move your bishop from c8 to e6.', 'Black defends d5 a second time with the bishop. Black still needs to move the bishop on f8 before castling.'],
                ['c3', 'Move your pawn from c2 to c3.', "A quiet, useful move. The pawn controls d4 and b4, so Black's knight on c6 cannot settle there, and it gives your bishop a retreat on c2, from where it would aim at the knight on e4."],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop and prepares to castle. This is the Classical Defense of the Open Variation.'],
                ['Re1', 'Move your rook from f1 to e1.', 'Put the rook on the e-file. It attacks the knight on e4, which only the pawn on d5 defends, so that pawn is tied to its post.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles and the king is safe. Material is level, and the knight on e4 still blocks your rook on the e-file.'],
                ['Nd4', 'Move your knight from f3 to d4.', 'Bring the knight to the centre. From d4 it attacks the knight on c6, which nothing defends, as well as the bishop on e6 and the pawn on b5, so Black must react at once. The price is that your e5 pawn loses its defender.'],
                ['Nxe5', 'Move your knight from c6 to e5, capturing the white pawn.', 'Black saves the attacked knight by taking your e5 pawn, and is a pawn up again. The knight on e4 is still attacked by your rook and defended only by the pawn on d5. The named Breslau Variation ends here: your next tasks are to challenge the knight on e4, for example by attacking it with f2-f3, and to develop your queenside pieces.'],
            ]],
            ['open-howell-ekstrom', 'Open Variation, Howell Attack: Qe2, Rd1 and c4 against d5', "Twelve White moves. Win back the e4 pawn, then put your queen on e2 and your rook on d1 and break with c4 to press against the d5 pawn, the only support of Black's knight on e4. Source: Open, Howell Attack, Ekstrom Variation.", 5, self::HOWELL_SOURCE, self::HOWELL_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open paths for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing further.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight with a threat: it attacks the pawn on e5. It also clears the way for castling.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 with a knight. This knight is the target of your next move.'],
                ['Bb5', 'Move your bishop from f1 to b5.', "This is the Ruy Lopez. The bishop attacks the knight that defends e5. There is no immediate threat to win the pawn, but this pressure on Black's centre lasts for many moves."],
                ['a6', 'Move your pawn from a7 to a6.', 'Black asks your bishop to decide at once: take the knight, or retreat and keep the pressure.'],
                ['Ba4', 'Move your bishop from b5 to a4.', 'Keep the bishop and its pressure on the knight on c6. The bishop also keeps the option of retreating to b3 later.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops and attacks your pawn on e4, which nothing defends at the moment.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle rather than defend e4 directly. Your king is safe and the rook can come to e1. In this line Black takes the pawn on e4, which is the Open Variation.'],
                ['Nxe4', 'Move your knight from f6 to e4, capturing the white pawn.', 'Black takes the pawn and is a pawn up for the moment. In the Open Variation the knight stands actively in the centre; your plan is to open lines at once and win the pawn back.'],
                ['d4', 'Move your pawn from d2 to d4.', "Strike in the centre while Black's king is still on e8. The pawn on e5 is now attacked by your pawn and knight and defended only by the knight on c6, which your bishop on a4 still attacks."],
                ['b5', 'Move your pawn from b7 to b5.', 'Black drives your bishop away from a4, so it no longer attacks the knight on c6, the only defender of e5.'],
                ['Bb3', 'Move your bishop from a4 to b3.', 'Retreat to the a2-g8 diagonal. From b3 the bishop attacks f7 and will press against d5, the square Black needs to support the knight on e4.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Black supports the knight on e4 with a pawn and opens a diagonal for the bishop on c8. In return Black leaves e5 to you: it is attacked by your pawn and knight and defended only by the knight on c6.'],
                ['dxe5', 'Move your pawn from d4 to e5, capturing the black pawn.', "Take on e5: material is level again. With your d-pawn gone, your queen and the bishop on b3 both attack the pawn on d5, which only Black's queen defends. Your e5 pawn also takes the d6 and f6 squares away from Black's pieces."],
                ['Be6', 'Move your bishop from c8 to e6.', 'Black defends d5 a second time with the bishop. Black still needs to move the bishop on f8 before castling.'],
                ['Qe2', 'Move your queen from d1 to e2.', 'This is the Howell Attack. The queen attacks the knight on e4 and the pawn on b5, and guards f2, which the knight attacks. It also leaves d1 free for a rook.'],
                ['Be7', 'Move your bishop from f8 to e7.', "Black develops the bishop and prepares to castle. The knight on e4 stays in the centre, defended by the pawn on d5, so that pawn is the key to Black's position."],
                ['Rd1', 'Move your rook from f1 to d1.', "Put the rook on the d-file, opposite Black's queen. It attacks the pawn on d5, which is now pinned against the queen on d8: if it moved, your rook would attack the queen. That pawn is also the only defender of the knight on e4."],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles and the king is safe. Now you open more lines against the d5 pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', "Strike at Black's pawns. The c-pawn attacks b5 and d5. If the pawn on d5 takes on c4, it leaves the d-file and your rook attacks the queen on d8."],
                ['bxc4', 'Move your pawn from b5 to c4, capturing the white pawn.', 'Black takes with the b-pawn, and for the moment Black is a pawn up. The pawn on c4 attacks your bishop on b3.'],
                ['Bxc4', 'Move your bishop from b3 to c4, capturing the black pawn.', 'Recapture with the bishop. Material is level again, and the bishop joins your rook in attacking the pawn on d5. That pawn attacks your bishop, but taking it would let your rook on d1 capture the queen on d8.'],
                ['Qd7', 'Move your queen from d8 to d7.', 'Black moves the queen to d7, where it still defends d5 and also protects the knight on c6 and both bishops. The rooks on a8 and f8 are now connected. The named Ekstrom Variation ends here: the d5 pawn is still pinned against the queen, so your next tasks are to add pressure on it, for example by bringing your knight from b1 to c3, and to develop the bishop on c1.'],
            ]],
            ['berlin-wall', 'Berlin Defense: the Berlin Wall queen exchange', 'Nine White moves. Meet 3...Nf6 by castling, win the pawn back with d4 and dxe5, take on c6 and exchange queens. In the resulting endgame you have the kingside pawn majority and Black has the two bishops. Source: Berlin Defense, Berlin Wall.', 6, self::BERLIN_SOURCE, self::BERLIN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open paths for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing further.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop a knight with a threat: it attacks the pawn on e5. It also clears the way for castling.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 with a knight. This knight is the target of your next move.'],
                ['Bb5', 'Move your bishop from f1 to b5.', "This is the Ruy Lopez. The bishop attacks the knight that defends e5. There is no immediate threat to win the pawn, but this pressure on Black's centre lasts for many moves."],
                ['Nf6', 'Move your knight from g8 to f6.', 'This is the Berlin Defense. Instead of ...a6, Black develops the knight at once and attacks your pawn on e4, which nothing defends at the moment.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', "Castle and leave e4 unprotected. Your king is safe, and if Black takes the pawn you strike in the centre with d4 to win it back while Black's king is still on e8."],
                ['Nxe4', 'Move your knight from f6 to e4, capturing the white pawn.', 'Black takes the pawn and is a pawn up for the moment. The knight on e4 attacks your pawns on d2 and f2, but both are defended.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Strike in the centre at once. Your pawn attacks e5, which only the knight on c6 defends, and your bishop on b5 still attacks that knight.'],
                ['Nd6', 'Move your knight from e4 to d6.', "Black retreats the knight to d6, where it attacks your bishop on b5, which nothing defends. For now the knight also blocks Black's d-pawn."],
                ['Bxc6', 'Move your bishop from b5 to c6, capturing the black knight.', 'Take the knight rather than retreat. Your bishop was attacked, and the capture removes the defender of e5.'],
                ['dxc6', 'Move your pawn from d7 to c6, capturing the white bishop.', 'Black recaptures with the d-pawn. This frees the diagonal of the bishop on c8 and starts to open the d-file, at the cost of doubled pawns on c7 and c6.'],
                ['dxe5', 'Move your pawn from d4 to e5, capturing the black pawn.', 'Take the pawn on e5: material is level again. Your pawn attacks the knight on d6, which your queen also attacks along the open d-file.'],
                ['Nf5', 'Move your knight from d6 to f5.', 'Black moves the knight out of attack to f5. Now nothing stands between the queens on the d-file: each queen attacks the other.'],
                ['Qxd8+', 'Move your queen from d1 to d8, capturing the black queen with check.', 'Exchange queens with check. Black has to take back with the king, which can then no longer castle.'],
                ['Kxd8', 'Move your king from e8 to d8, capturing the white queen.', 'Black recaptures with the king. With the queens off, a king in the centre is in little danger. Compare the pawns: you have four against three on the kingside, while Black has four against three on the queenside, but two of them are doubled on the c-file. In return Black has the two bishops.'],
                ['Nc3', 'Move your knight from b1 to c3.', "Develop the knight towards the centre. From c3 it controls d5 and e4, squares that Black's pieces would like to use."],
                ['Bd7', 'Move your bishop from c8 to d7.', 'Black develops the bishop to d7, where it protects the knight on f5 and the pawn on c6, and frees c8 for the king. The named Berlin Wall ends here: your next tasks are to develop the bishop on c1, bring your rooks to the centre files, for example to d1, and make your kingside pawn majority count.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='ruy-lopez'")) {
            throw new \RuntimeException('Ruy Lopez revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='ruy-lopez' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Ruy Lopez revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Ruy Lopez teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='ruy-lopez' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='ruy-lopez' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='ruy-lopez' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('ruy-lopez',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('ruy-lopez',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Ruy Lopez edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='ruy-lopez'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Ruy Lopez sessions and progress.');
    }
}
