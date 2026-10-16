<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Queen's Gambit revision 3: keep the three current lines and add four complete named Declined lines. */
final class Version20261016031700 extends AbstractMigration
{
    public const EXCHANGE_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nc3 d5 4. cxd5 exd5 5. Bg5 c6 6. e3 Be7 7. Bd3 Nbd7 8. Nge2 O-O 9. O-O h6 10. Bh4 Re8 11. Qc2';
    public const BERNSTEIN_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nc3 d5 4. Bg5 Be7 5. e3 h6 6. Bh4 O-O 7. Nf3 Ne4 8. Bxe7 Qxe7 9. cxd5 Nxc3 10. bxc3 exd5 11. Qb3 Qd6';
    public const TARTAKOWER_SOURCE = '1. d4 d5 2. c4 e6 3. Nc3 Be7 4. Nf3 Nf6 5. Bg5 h6 6. Bh4 O-O 7. e3 b6 8. cxd5 Nxd5 9. Bxe7 Qxe7 10. Nxd5 exd5 11. Rc1 Be6';
    public const CAMBRIDGE_SOURCE = '1. d4 d5 2. c4 e6 3. Nc3 Nf6 4. Bg5 Nbd7 5. e3 c6 6. Nf3 Qa5 7. Nd2 Bb4 8. Qc2 O-O 9. Bh4';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['declined-development' => [19, 0], 'orthodox-classical' => [28, 1], 'accepted-classical' => [26, 2]];

    public const EXCHANGE_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3P4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3P4/3P4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/5n2/3p4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'rnbqkb1r/ppp2ppp/5n2/3p4/3P4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/5n2/3p2B1/3P4/2N5/PP2PPPP/R2QKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkb1r/ppp2ppp/5n2/3p2B1/3P4/2N5/PP2PPPP/R2QKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p2n2/3p2B1/3P4/2N5/PP2PPPP/R2QKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkb1r/pp3ppp/2p2n2/3p2B1/3P4/2N5/PP2PPPP/R2QKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p2n2/3p2B1/3P4/2N1P3/PP3PPP/R2QKBNR b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rnbqkb1r/pp3ppp/2p2n2/3p2B1/3P4/2N1P3/PP3PPP/R2QKBNR b KQkq -', 'toKey' => 'rnbqk2r/pp2bppp/2p2n2/3p2B1/3P4/2N1P3/PP3PPP/R2QKBNR w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'rnbqk2r/pp2bppp/2p2n2/3p2B1/3P4/2N1P3/PP3PPP/R2QKBNR w KQkq -', 'toKey' => 'rnbqk2r/pp2bppp/2p2n2/3p2B1/3P4/2NBP3/PP3PPP/R2QK1NR b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqk2r/pp2bppp/2p2n2/3p2B1/3P4/2NBP3/PP3PPP/R2QK1NR b KQkq -', 'toKey' => 'r1bqk2r/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP3PPP/R2QK1NR w KQkq -'],
        ['uci' => 'g1e2', 'san' => 'Nge2', 'fromKey' => 'r1bqk2r/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP3PPP/R2QK1NR w KQkq -', 'toKey' => 'r1bqk2r/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2QK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2QK2R b KQkq -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2QK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2QK2R w KQ -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2Q1RK1 b - -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p2n2/3p2B1/3P4/2NBP3/PP2NPPP/R2Q1RK1 b - -', 'toKey' => 'r1bq1rk1/pp1nbpp1/2p2n1p/3p2B1/3P4/2NBP3/PP2NPPP/R2Q1RK1 w - -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'r1bq1rk1/pp1nbpp1/2p2n1p/3p2B1/3P4/2NBP3/PP2NPPP/R2Q1RK1 w - -', 'toKey' => 'r1bq1rk1/pp1nbpp1/2p2n1p/3p4/3P3B/2NBP3/PP2NPPP/R2Q1RK1 b - -'],
        ['uci' => 'f8e8', 'san' => 'Re8', 'fromKey' => 'r1bq1rk1/pp1nbpp1/2p2n1p/3p4/3P3B/2NBP3/PP2NPPP/R2Q1RK1 b - -', 'toKey' => 'r1bqr1k1/pp1nbpp1/2p2n1p/3p4/3P3B/2NBP3/PP2NPPP/R2Q1RK1 w - -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r1bqr1k1/pp1nbpp1/2p2n1p/3p4/3P3B/2NBP3/PP2NPPP/R2Q1RK1 w - -', 'toKey' => 'r1bqr1k1/pp1nbpp1/2p2n1p/3p4/3P3B/2NBP3/PPQ1NPPP/R4RK1 b - -'],
    ];

    public const BERNSTEIN_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR b KQkq -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR b KQkq -', 'toKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR w KQkq -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR w KQkq -', 'toKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p4/2PP3B/2N1P3/PP3PPP/R2QKBNR b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p4/2PP3B/2N1P3/PP3PPP/R2QKBNR b KQkq -', 'toKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1P3/PP3PPP/R2QKBNR w KQ -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1P3/PP3PPP/R2QKBNR w KQ -', 'toKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'f6e4', 'san' => 'Ne4', 'fromKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnbq1rk1/ppp1bpp1/4p2p/3p4/2PPn2B/2N1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'h4e7', 'san' => 'Bxe7', 'fromKey' => 'rnbq1rk1/ppp1bpp1/4p2p/3p4/2PPn2B/2N1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1Bpp1/4p2p/3p4/2PPn3/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'd8e7', 'san' => 'Qxe7', 'fromKey' => 'rnbq1rk1/ppp1Bpp1/4p2p/3p4/2PPn3/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/4p2p/3p4/2PPn3/2N1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnb2rk1/ppp1qpp1/4p2p/3p4/2PPn3/2N1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3Pn3/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'e4c3', 'san' => 'Nxc3', 'fromKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3Pn3/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3P4/2n1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'b2c3', 'san' => 'bxc3', 'fromKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3P4/2n1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3P4/2P1PN2/P4PPP/R2QKB1R b KQ -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnb2rk1/ppp1qpp1/4p2p/3P4/3P4/2P1PN2/P4PPP/R2QKB1R b KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/7p/3p4/3P4/2P1PN2/P4PPP/R2QKB1R w KQ -'],
        ['uci' => 'd1b3', 'san' => 'Qb3', 'fromKey' => 'rnb2rk1/ppp1qpp1/7p/3p4/3P4/2P1PN2/P4PPP/R2QKB1R w KQ -', 'toKey' => 'rnb2rk1/ppp1qpp1/7p/3p4/3P4/1QP1PN2/P4PPP/R3KB1R b KQ -'],
        ['uci' => 'e7d6', 'san' => 'Qd6', 'fromKey' => 'rnb2rk1/ppp1qpp1/7p/3p4/3P4/1QP1PN2/P4PPP/R3KB1R b KQ -', 'toKey' => 'rnb2rk1/ppp2pp1/3q3p/3p4/3P4/1QP1PN2/P4PPP/R3KB1R w KQ -'],
    ];

    public const TARTAKOWER_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk1nr/ppp1bppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqk1nr/ppp1bppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk1nr/ppp1bppp/4p3/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqk1nr/ppp1bppp/4p3/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R b KQkq -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R w KQkq -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p4/2PP3B/2N2N2/PP2PPPP/R2QKB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1bpp1/4pn1p/3p4/2PP3B/2N2N2/PP2PPPP/R2QKB1R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N2N2/PP2PPPP/R2QKB1R w KQ -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N2N2/PP2PPPP/R2QKB1R w KQ -', 'toKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'rnbq1rk1/ppp1bpp1/4pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnbq1rk1/p1p1bpp1/1p2pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rnbq1rk1/p1p1bpp1/1p2pn1p/3p4/2PP3B/2N1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnbq1rk1/p1p1bpp1/1p2pn1p/3P4/3P3B/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'f6d5', 'san' => 'Nxd5', 'fromKey' => 'rnbq1rk1/p1p1bpp1/1p2pn1p/3P4/3P3B/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnbq1rk1/p1p1bpp1/1p2p2p/3n4/3P3B/2N1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'h4e7', 'san' => 'Bxe7', 'fromKey' => 'rnbq1rk1/p1p1bpp1/1p2p2p/3n4/3P3B/2N1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnbq1rk1/p1p1Bpp1/1p2p2p/3n4/3P4/2N1PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'd8e7', 'san' => 'Qxe7', 'fromKey' => 'rnbq1rk1/p1p1Bpp1/1p2p2p/3n4/3P4/2N1PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnb2rk1/p1p1qpp1/1p2p2p/3n4/3P4/2N1PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'c3d5', 'san' => 'Nxd5', 'fromKey' => 'rnb2rk1/p1p1qpp1/1p2p2p/3n4/3P4/2N1PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnb2rk1/p1p1qpp1/1p2p2p/3N4/3P4/4PN2/PP3PPP/R2QKB1R b KQ -'],
        ['uci' => 'e6d5', 'san' => 'exd5', 'fromKey' => 'rnb2rk1/p1p1qpp1/1p2p2p/3N4/3P4/4PN2/PP3PPP/R2QKB1R b KQ -', 'toKey' => 'rnb2rk1/p1p1qpp1/1p5p/3p4/3P4/4PN2/PP3PPP/R2QKB1R w KQ -'],
        ['uci' => 'a1c1', 'san' => 'Rc1', 'fromKey' => 'rnb2rk1/p1p1qpp1/1p5p/3p4/3P4/4PN2/PP3PPP/R2QKB1R w KQ -', 'toKey' => 'rnb2rk1/p1p1qpp1/1p5p/3p4/3P4/4PN2/PP3PPP/2RQKB1R b K -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'rnb2rk1/p1p1qpp1/1p5p/3p4/3P4/4PN2/PP3PPP/2RQKB1R b K -', 'toKey' => 'rn3rk1/p1p1qpp1/1p2b2p/3p4/3P4/4PN2/PP3PPP/2RQKB1R w K -'],
    ];

    public const CAMBRIDGE_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR b KQkq -', 'toKey' => 'r1bqkb1r/pppn1ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'r1bqkb1r/pppn1ppp/4pn2/3p2B1/2PP4/2N5/PP2PPPP/R2QKBNR w KQkq -', 'toKey' => 'r1bqkb1r/pppn1ppp/4pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'r1bqkb1r/pppn1ppp/4pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p2B1/2PP4/2N1P3/PP3PPP/R2QKBNR w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p2B1/2PP4/2N1PN2/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'd8a5', 'san' => 'Qa5', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p2B1/2PP4/2N1PN2/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'r1b1kb1r/pp1n1ppp/2p1pn2/q2p2B1/2PP4/2N1PN2/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'f3d2', 'san' => 'Nd2', 'fromKey' => 'r1b1kb1r/pp1n1ppp/2p1pn2/q2p2B1/2PP4/2N1PN2/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'r1b1kb1r/pp1n1ppp/2p1pn2/q2p2B1/2PP4/2N1P3/PP1N1PPP/R2QKB1R b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4', 'fromKey' => 'r1b1kb1r/pp1n1ppp/2p1pn2/q2p2B1/2PP4/2N1P3/PP1N1PPP/R2QKB1R b KQkq -', 'toKey' => 'r1b1k2r/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PP1N1PPP/R2QKB1R w KQkq -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r1b1k2r/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PP1N1PPP/R2QKB1R w KQkq -', 'toKey' => 'r1b1k2r/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PPQN1PPP/R3KB1R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1b1k2r/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PPQN1PPP/R3KB1R b KQkq -', 'toKey' => 'r1b2rk1/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PPQN1PPP/R3KB1R w KQ -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'r1b2rk1/pp1n1ppp/2p1pn2/q2p2B1/1bPP4/2N1P3/PPQN1PPP/R3KB1R w KQ -', 'toKey' => 'r1b2rk1/pp1n1ppp/2p1pn2/q2p4/1bPP3B/2N1P3/PPQN1PPP/R3KB1R b KQ -'],
    ];

    public function getDescription(): string
    {
        return "Publish Queen's Gambit revision 3: add Exchange Reshevsky, Lasker Bernstein, Tartakower and Cambridge Springs lines";
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $opening = [
            ['d4', 'Move your pawn from d2 to d4.', 'Take space in the centre and open a path for the bishop on c1.'],
            ['d5', 'Move your pawn from d7 to d5.', 'Black takes central space too and stops your d-pawn from moving straight ahead.'],
            ['c4', 'Move your pawn from c2 to c4.', "Challenge Black's central pawn from the side. This is the Queen's Gambit: Black could capture this pawn, but does not have to."],
            ['e6', 'Move your pawn from e7 to e6.', 'Black declines the offered pawn for now, supports d5 and opens a path for the bishop on f8.'],
            ['Nc3', 'Move your knight from b1 to c3.', "Develop a knight towards the centre and add pressure to Black's pawn on d5."],
        ];
        $knightFirst = [
            ['d4', 'Move your pawn from d2 to d4.', 'Take space in the centre and open a path for the bishop on c1.'],
            ['Nf6', 'Move your knight from g8 to f6.', 'Black develops a knight and controls e4, keeping the choice of pawn structure open for another move.'],
            ['c4', 'Move your pawn from c2 to c4.', "Gain more central space. The pawn controls d5, and together with d4 it gives you the Queen's Gambit centre."],
            ['e6', 'Move your pawn from e7 to e6.', 'Black opens a path for the bishop on f8 and prepares ...d5.'],
            ['Nc3', 'Move your knight from b1 to c3.', 'Develop a knight towards the centre. It controls d5 and e4, the two squares Black is fighting for.'],
            ['d5', 'Move your pawn from d7 to d5.', "Black takes central space and attacks your c4 pawn. The position is now a Queen's Gambit Declined, reached by a different move order."],
        ];
        $introduction = "Learn the Queen's Gambit as White against Black's main replies, each a complete named line from the opening reference. In the Semi-Tarrasch you castle and then break with e4. In the Orthodox Defense, Black frees a cramped position with exchanges and ...e5. In the Queen's Gambit Accepted, Black takes on c4 and you win the pawn back. In the Exchange Variation you take on d5 early and develop behind a fixed pawn centre. In the Lasker Defense, Black trades pieces with ...Ne4. In the Tartakower Defense, Black plays ...b6 and you use the c-file after exchanges on d5. In the Cambridge Springs, Black pins your c3 knight with ...Qa5. Black replies automatically along the selected line; real opponents can choose other continuations.";
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['exchange-reshevsky', 'Exchange Variation: cxd5, Bd3 and Nge2', "Eleven White moves. Take on d5 to fix the centre, pin with Bg5, develop your king's knight to e2, castle and keep your f-pawn free for a later f3 and e4. Source: Exchange Variation, Reshevsky Variation.", 3, self::EXCHANGE_SOURCE, self::EXCHANGE_FACTS, [...$knightFirst,
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'This is the Exchange Variation. Instead of keeping the tension, you release it at once. In this line Black recaptures with the e-pawn, which frees the bishop on c8, but the central pawn structure is fixed early and the plans for both sides become clear.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', "Black recaptures towards the centre and material is level. You no longer have a c-pawn, so the c-file is half-open for your rooks; Black no longer has an e-pawn, so the e-file is half-open for Black's rooks. Black's bishop on c8 now has an open diagonal."],
                ['Bg5', 'Move your bishop from c1 to g5.', 'Develop the bishop with a pin: the knight on f6 cannot move without exposing the queen on d8. That knight defends d5, which your knight on c3 attacks.'],
                ['c6', 'Move your pawn from c7 to c6.', "Black supports d5 with a pawn, so the centre no longer depends on the pinned knight. Black's pawns on c6 and d5 against your pawn on d4 form the Carlsbad structure, typical of the Exchange Variation."],
                ['e3', 'Move your pawn from e2 to e3.', 'Support d4 and open the diagonal of the bishop on f1, which is heading for d3.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black puts the bishop between the knight and the queen, which ends the pin, and prepares to castle.'],
                ['Bd3', 'Move your bishop from f1 to d3.', "Develop the bishop to its most natural square in this structure. From d3 it aims at h7 and controls f5, the square Black's bishop on c8 would like to use."],
                ['Nbd7', 'Move your knight from b8 to d7.', "Black develops the knight to d7, where it supports the knight on f6. The c6 square is already taken by Black's own pawn, so d7 is this knight's natural square."],
                ['Nge2', 'Move your knight from g1 to e2.', "Develop the knight to e2 rather than f3. From e2 it supports d4 and the knight on c3, and it leaves your f-pawn free: later, f3 can support an e3-e4 advance against Black's pawn on d5."],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles. Both sides have developed most of their pieces, and the plans now depend on the fixed pawn centre.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle and put your king in safety. The rook on f1 now stands behind your f-pawn, which suits the plan of a later f3 and e4.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Black asks your bishop to decide between taking on f6 and retreating. The pawn attacks the bishop on g5, which nothing defends at the moment.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'Keep the bishop by retreating along the same diagonal. On h4 it still attacks the knight on f6, one of the two defenders of d5.'],
                ['Re8', 'Move your rook from f8 to e8.', 'Black puts a rook on the half-open e-file, behind the bishop on e7. It also frees f8, so the knight on d7 can later reroute via f8.'],
                ['Qc2', 'Move your queen from d1 to c2.', "Develop the queen to c2. It connects your rooks, stands on the half-open c-file and defends the knight on c3 and the bishop on d3. The named Reshevsky Variation ends here: your next tasks are to place your rooks, then choose between advancing your queenside pawns with b4 and b5 against Black's c6 pawn and the central plan of f3 and e4."],
            ]],
            ['lasker-bernstein', 'Lasker Defense: ...Ne4 exchanges and Qb3', 'Eleven White moves. Pin with Bg5, keep the bishop with Bh4, meet the ...Ne4 exchanges by recapturing towards the centre with bxc3, then aim your queen at b7 and d5 from b3. Source: Lasker Defense, Bernstein Variation.', 4, self::BERNSTEIN_SOURCE, self::BERNSTEIN_FACTS, [...$knightFirst,
                ['Bg5', 'Move your bishop from c1 to g5.', 'Develop the bishop with a pin: the knight on f6 cannot move without exposing the queen on d8. That knight defends d5 and controls e4, so the pin also adds pressure on the centre.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Black puts the bishop between the knight and the queen, which ends the pin, and prepares to castle.'],
                ['e3', 'Move your pawn from e2 to e3.', 'Support d4 and open a path for the bishop on f1, which now defends your c4 pawn against the pawn on d5.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Black asks your bishop to decide between taking on f6 and retreating. The pawn attacks the bishop on g5, which nothing defends at the moment.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'Keep the bishop on the same diagonal, still aiming at the knight on f6. On h4 it also defends your f2 pawn.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles, putting the king in safety before choosing a plan.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop your last knight. It defends d4 and the bishop on h4, and clears the way for castling.'],
                ['Ne4', 'Move your knight from f6 to e4.', 'This is the Lasker Defense. The knight jumps to e4, where the pawn on d5 supports it, and attacks your knight on c3. It also uncovers the bishop on e7, which now attacks your bishop on h4. Black wants to exchange pieces, because a cramped position is easier to handle with fewer pieces on the board.'],
                ['Bxe7', 'Move your bishop from h4 to e7, capturing the black bishop.', 'Exchange bishops. Your bishop on e7 attacks the queen and the rook on f8, so Black recaptures straight away.'],
                ['Qxe7', 'Move your queen from d8 to e7, capturing the white bishop.', 'Black recaptures with the queen, which now protects c7, e6 and f7. Material is level, and Black still has the option of taking on c3.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', "Capture on d5 now. That pawn was the only defender of Black's knight on e4, so the knight is now attacked by your knight on c3 and has no support. Your new pawn on d5 also attacks e6."],
                ['Nxc3', 'Move your knight from e4 to c3, capturing the white knight.', 'Black exchanges knights with a capture that also attacks your queen on d1, so your next move is to recapture.'],
                ['bxc3', 'Move your pawn from b2 to c3, capturing the black knight.', 'Recapture with the b-pawn, towards the centre: the pawn on c3 supports d4, and the b-file is now half-open for you. Your pawn on d5 has no defender and is attacked by the e6 pawn, so Black will win it back.'],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white pawn.', "Black regains the pawn and material is level. The bishop on c8 now has an open diagonal, and Black's queen on e7 pins your e3 pawn to your king along the half-open e-file."],
                ['Qb3', 'Move your queen from d1 to b3.', 'Bring the queen to b3 on the half-open b-file. It attacks the pawn on b7, which only the bishop on c8 defends, and the pawn on d5, which has no defender at the moment.'],
                ['Qd6', 'Move your queen from e7 to d6.', 'Black defends d5 with the queen, which also eyes your h2 pawn. The queen has left the e-file, so your e3 pawn is no longer pinned. The named Bernstein Variation ends here: your next tasks are to develop the bishop on f1 and castle, and to keep pressure on b7 and d5; the bishop on c8 is tied to the defence of b7.'],
            ]],
            ['tartakower-defense', 'Tartakower Defense: ...b6, exchanges on d5 and Rc1', 'Eleven White moves. Meet ...h6 with Bh4, exchange on d5 after Black prepares a queenside fianchetto with ...b6, then put a rook on the half-open c-file against c7. Source: Tartakower Defense.', 5, self::TARTAKOWER_SOURCE, self::TARTAKOWER_FACTS, [...$opening,
                ['Be7', 'Move your bishop from f8 to e7.', 'Black develops the bishop before the knight. From e7 it will support the knight once it comes to f6, and f8 is cleared for castling.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop your other knight. It defends d4, controls e5 and clears the way for castling.'],
                ['Nf6', 'Move your knight from g8 to f6.', "Black develops the knight and defends d5 a third time, after the queen and the e6 pawn. The position is now a standard Queen's Gambit Declined."],
                ['Bg5', 'Move your bishop from c1 to g5.', 'Develop the bishop and put pressure on the knight on f6, a defender of d5. The bishop on e7 already stands between the knight and the queen, so this is not a pin.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Black asks your bishop to decide straight away. The pawn on h6 attacks it; your knight on f3 defends it.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'Keep the bishop and its pressure on f6 by retreating along the same diagonal. Taking on f6 instead would hand Black the pair of bishops.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles. Thanks to ...h6, the king already has an escape square on h7 for later.'],
                ['e3', 'Move your pawn from e2 to e3.', 'Support d4 and open the diagonal for the bishop on f1, which now defends your c4 pawn.'],
                ['b6', 'Move your pawn from b7 to b6.', "This is the Tartakower Defense. The pawn on e6 shuts in Black's bishop on c8, so Black prepares to develop it on the queenside, on b7 or a6, from where it would watch the centre."],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'Release the central tension now. In this line, the exchanges that follow leave a black pawn on d5, which blocks the long diagonal that ...b6 prepared for the bishop.'],
                ['Nxd5', 'Move your knight from f6 to d5, capturing the white pawn.', "Black recaptures with the knight, which attacks your knight on c3 and your e3 pawn and offers more exchanges. Moving the knight also uncovers Black's bishop on e7, which now attacks your bishop on h4."],
                ['Bxe7', 'Move your bishop from h4 to e7, capturing the black bishop.', 'Exchange bishops. Your bishop on e7 attacks the queen and the rook on f8, so Black recaptures straight away.'],
                ['Qxe7', 'Move your queen from d8 to e7, capturing the white bishop.', 'Black recaptures with the queen and material is level. The knight on d5 is still attacked by your knight on c3 and defended only by the pawn on e6.'],
                ['Nxd5', 'Move your knight from c3 to d5, capturing the black knight.', "Exchange knights. Your knight on d5 attacks Black's queen on e7, so Black recaptures with the pawn."],
                ['exd5', 'Move your pawn from e6 to d5, capturing the white knight.', "Black recaptures and material is level. The pawn on d5 now blocks the diagonal from b7, and Black's queen on e7 pins your e3 pawn to your king along the half-open e-file."],
                ['Rc1', 'Move your rook from a1 to c1.', 'Put the rook on the half-open c-file, where you no longer have a pawn. It attacks the pawn on c7, which only the queen defends, and the c-file will be your main route into the queenside.'],
                ['Be6', 'Move your bishop from c8 to e6.', "Black develops the bishop to e6, where it defends d5 and f7. It also blocks the e-file, so your e3 pawn is no longer pinned. The named Tartakower Defense ends here: your next tasks are to develop the bishop on f1 and castle, then keep up the pressure on the c-file and on Black's queenside pawns."],
            ]],
            ['cambridge-springs', 'Cambridge Springs: ...Qa5 and the pinned c3 knight', 'Nine White moves. Meet ...Nbd7, ...c6 and ...Qa5, end the pin on your c3 knight with Nd2, defend it again with Qc2 and keep your bishop on the h4-d8 diagonal. Source: Cambridge Springs Defense, Argentine Variation.', 6, self::CAMBRIDGE_SOURCE, self::CAMBRIDGE_FACTS, [...$opening,
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops a knight, supports the pawn on d5 and controls e4.'],
                ['Bg5', 'Move your bishop from c1 to g5.', 'Pin the knight on f6 against the queen on d8. That knight defends d5, so pressure on f6 is also pressure on the centre.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Black develops the knight to d7 and keeps the bishop on f8 at home for now. The knight on f6 is pinned only to the queen, not the king: trying to win the d5 pawn with cxd5, exd5 and Nxd5 fails: after ...Nxd5, Bxd8 is met by ...Bb4+, and Black wins back the queen and comes out a knight up for a pawn.'],
                ['e3', 'Move your pawn from e2 to e3.', 'Support d4 and open the diagonal of the bishop on f1, which now defends your c4 pawn.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Black supports d5 with another pawn. The move also opens the diagonal from d8 to a5, which the queen is about to use.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop your other knight. It defends d4 and your bishop on g5.'],
                ['Qa5', 'Move your queen from d8 to a5.', 'This is the Cambridge Springs Defense. The queen pins your knight on c3 to your king, and only the pawn on b2 defends that knight. The queen has also left d8, so your bishop no longer pins the knight on f6. Black can add pressure with ...Ne4 or ...Bb4, and if the d5 pawn moves, the queen also looks along the fifth rank at your bishop on g5.'],
                ['Nd2', 'Move your knight from f3 to d2.', "Put your knight on d2, between the knight on c3 and your king, so the pin is broken. On d2 it also defends c4 and covers e4, the square Black's knight wants. The cost is that your bishop on g5 has lost the knight's protection."],
                ['Bb4', 'Move your bishop from f8 to b4.', 'Black develops the bishop and attacks your knight on c3, with the queen lined up behind the bishop on the same diagonal. So far only your b2 pawn defends the knight.'],
                ['Qc2', 'Move your queen from d1 to c2.', 'Defend the knight on c3 a second time. From c2 the queen also protects the knight on d2 and eyes h7 along the diagonal.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Black castles, putting the king in safety. The pawn on h7 is defended by the king and the knight on f6.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'Step the bishop off the fifth rank. Nothing has defended it since your knight left f3, and if Black moved the d5 pawn, the queen on a5 would attack it along that rank. On h4 it keeps its eye on the knight on f6. The named Argentine Variation ends here: your next tasks are to develop the bishop on f1 and castle, while keeping c3 and c4 protected.'],
            ]],
        ];

        if (2 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='queens-gambit'")) {
            throw new \RuntimeException("Queen's Gambit revision 3 must be published directly after revision 2.");
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='queens-gambit' AND revision=2 AND id=?", [$lineId])) {
                throw new \RuntimeException("A retained Queen's Gambit revision 2 line is missing or changed: ".$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException("Queen's Gambit teaching text and frozen path disagree: ".$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,3,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='queens-gambit' AND revision=2", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,3,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='queens-gambit' AND revision=2 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,3,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='queens-gambit' AND revision=2 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('queens-gambit',3,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('queens-gambit',3,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException("Missing resolved Queen's Gambit edge."), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=3 WHERE id='queens-gambit'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException("Publish a new revision; preserve previous Queen's Gambit sessions and progress.");
    }
}
