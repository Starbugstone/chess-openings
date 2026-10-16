<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Sicilian Defense revision 2: keep the three main lines and add the Richter-Rauzer, Sozin, Scheveningen, Taimanov and Alapin as complete named pinned paths. */
final class Version20261016031600 extends AbstractMigration
{
    public const RAUZER_SOURCE = '1. e4 c5 2. Nf3 d6 3. d4 cxd4 4. Nxd4 Nf6 5. Nc3 Nc6 6. Bg5 e6 7. Qd2 Be7 8. O-O-O Nxd4 9. Qxd4 a6 10. f4 b5';
    public const SOZIN_SOURCE = '1. e4 c5 2. Nf3 d6 3. d4 cxd4 4. Nxd4 Nf6 5. Nc3 Nc6 6. Bc4 e6 7. Be3 Be7 8. Bb3 O-O 9. O-O Na5 10. f4 b6';
    public const SCHEVENINGEN_SOURCE = '1. e4 c5 2. Nf3 e6 3. d4 cxd4 4. Nxd4 Nc6 5. Nc3 Qc7 6. Be2 a6 7. O-O Nf6 8. Be3 Be7 9. f4 d6 10. Qe1 O-O';
    public const TAIMANOV_SOURCE = '1. e4 c5 2. Nf3 e6 3. d4 cxd4 4. Nxd4 Nc6 5. Nb5 d6 6. c4 Nf6 7. N1c3 a6 8. Na3 Be7 9. Be2 O-O 10. O-O b6';
    public const ALAPIN_SOURCE = '1. e4 c5 2. c3 d5 3. exd5 Qxd5 4. d4 cxd4 5. cxd4 Nc6 6. Nf3 Bg4 7. Nc3 Bxf3 8. gxf3 Qxd4 9. Qxd4 Nxd4';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['najdorf-main-line' => [18, 0], 'dragon-yugoslav' => [24, 1], 'sveshnikov' => [20, 2]];

    public const RAUZER_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f3d4', 'san' => 'Nxd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp2pppp/2np1n2/6B1/3NP3/2N5/PPP2PPP/R2QKB1R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'r1bqkb1r/pp2pppp/2np1n2/6B1/3NP3/2N5/PPP2PPP/R2QKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/6B1/3NP3/2N5/PPP2PPP/R2QKB1R w KQkq -'],
        ['uci' => 'd1d2', 'san' => 'Qd2', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/6B1/3NP3/2N5/PPP2PPP/R2QKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/R3KB1R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/R3KB1R b KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/R3KB1R w KQkq -'],
        ['uci' => 'e1c1', 'san' => 'O-O-O', 'fromKey' => 'r1bqk2r/pp2bppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/R3KB1R w KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/2KR1B1R b kq -'],
        ['uci' => 'c6d4', 'san' => 'Nxd4', 'fromKey' => 'r1bqk2r/pp2bppp/2nppn2/6B1/3NP3/2N5/PPPQ1PPP/2KR1B1R b kq -', 'toKey' => 'r1bqk2r/pp2bppp/3ppn2/6B1/3nP3/2N5/PPPQ1PPP/2KR1B1R w kq -'],
        ['uci' => 'd2d4', 'san' => 'Qxd4', 'fromKey' => 'r1bqk2r/pp2bppp/3ppn2/6B1/3nP3/2N5/PPPQ1PPP/2KR1B1R w kq -', 'toKey' => 'r1bqk2r/pp2bppp/3ppn2/6B1/3QP3/2N5/PPP2PPP/2KR1B1R b kq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1bqk2r/pp2bppp/3ppn2/6B1/3QP3/2N5/PPP2PPP/2KR1B1R b kq -', 'toKey' => 'r1bqk2r/1p2bppp/p2ppn2/6B1/3QP3/2N5/PPP2PPP/2KR1B1R w kq -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'r1bqk2r/1p2bppp/p2ppn2/6B1/3QP3/2N5/PPP2PPP/2KR1B1R w kq -', 'toKey' => 'r1bqk2r/1p2bppp/p2ppn2/6B1/3QPP2/2N5/PPP3PP/2KR1B1R b kq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqk2r/1p2bppp/p2ppn2/6B1/3QPP2/2N5/PPP3PP/2KR1B1R b kq -', 'toKey' => 'r1bqk2r/4bppp/p2ppn2/1p4B1/3QPP2/2N5/PPP3PP/2KR1B1R w kq -'],
    ];

    public const SOZIN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f3d4', 'san' => 'Nxd4', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/3p4/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkb1r/pp2pppp/3p1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/2BNP3/2N5/PPP2PPP/R1BQK2R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'r1bqkb1r/pp2pppp/2np1n2/8/2BNP3/2N5/PPP2PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/8/2BNP3/2N5/PPP2PPP/R1BQK2R w KQkq -'],
        ['uci' => 'c1e3', 'san' => 'Be3', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/8/2BNP3/2N5/PPP2PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/8/2BNP3/2N1B3/PPP2PPP/R2QK2R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/8/2BNP3/2N1B3/PPP2PPP/R2QK2R b KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2nppn2/8/2BNP3/2N1B3/PPP2PPP/R2QK2R w KQkq -'],
        ['uci' => 'c4b3', 'san' => 'Bb3', 'fromKey' => 'r1bqk2r/pp2bppp/2nppn2/8/2BNP3/2N1B3/PPP2PPP/R2QK2R w KQkq -', 'toKey' => 'r1bqk2r/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2QK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2QK2R b KQkq -', 'toKey' => 'r1bq1rk1/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2QK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bq1rk1/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2QK2R w KQ -', 'toKey' => 'r1bq1rk1/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2Q1RK1 b - -'],
        ['uci' => 'c6a5', 'san' => 'Na5', 'fromKey' => 'r1bq1rk1/pp2bppp/2nppn2/8/3NP3/1BN1B3/PPP2PPP/R2Q1RK1 b - -', 'toKey' => 'r1bq1rk1/pp2bppp/3ppn2/n7/3NP3/1BN1B3/PPP2PPP/R2Q1RK1 w - -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'r1bq1rk1/pp2bppp/3ppn2/n7/3NP3/1BN1B3/PPP2PPP/R2Q1RK1 w - -', 'toKey' => 'r1bq1rk1/pp2bppp/3ppn2/n7/3NPP2/1BN1B3/PPP3PP/R2Q1RK1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r1bq1rk1/pp2bppp/3ppn2/n7/3NPP2/1BN1B3/PPP3PP/R2Q1RK1 b - -', 'toKey' => 'r1bq1rk1/p3bppp/1p1ppn2/n7/3NPP2/1BN1B3/PPP3PP/R2Q1RK1 w - -'],
    ];

    public const SCHEVENINGEN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f3d4', 'san' => 'Nxd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'd8c7', 'san' => 'Qc7', 'fromKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/2N5/PPP2PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1b1kbnr/ppqp1ppp/2n1p3/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'r1b1kbnr/ppqp1ppp/2n1p3/8/3NP3/2N5/PPP2PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1b1kbnr/ppqp1ppp/2n1p3/8/3NP3/2N5/PPP1BPPP/R1BQK2R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1b1kbnr/ppqp1ppp/2n1p3/8/3NP3/2N5/PPP1BPPP/R1BQK2R b KQkq -', 'toKey' => 'r1b1kbnr/1pqp1ppp/p1n1p3/8/3NP3/2N5/PPP1BPPP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1b1kbnr/1pqp1ppp/p1n1p3/8/3NP3/2N5/PPP1BPPP/R1BQK2R w KQkq -', 'toKey' => 'r1b1kbnr/1pqp1ppp/p1n1p3/8/3NP3/2N5/PPP1BPPP/R1BQ1RK1 b kq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1b1kbnr/1pqp1ppp/p1n1p3/8/3NP3/2N5/PPP1BPPP/R1BQ1RK1 b kq -', 'toKey' => 'r1b1kb1r/1pqp1ppp/p1n1pn2/8/3NP3/2N5/PPP1BPPP/R1BQ1RK1 w kq -'],
        ['uci' => 'c1e3', 'san' => 'Be3', 'fromKey' => 'r1b1kb1r/1pqp1ppp/p1n1pn2/8/3NP3/2N5/PPP1BPPP/R1BQ1RK1 w kq -', 'toKey' => 'r1b1kb1r/1pqp1ppp/p1n1pn2/8/3NP3/2N1B3/PPP1BPPP/R2Q1RK1 b kq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1b1kb1r/1pqp1ppp/p1n1pn2/8/3NP3/2N1B3/PPP1BPPP/R2Q1RK1 b kq -', 'toKey' => 'r1b1k2r/1pqpbppp/p1n1pn2/8/3NP3/2N1B3/PPP1BPPP/R2Q1RK1 w kq -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'r1b1k2r/1pqpbppp/p1n1pn2/8/3NP3/2N1B3/PPP1BPPP/R2Q1RK1 w kq -', 'toKey' => 'r1b1k2r/1pqpbppp/p1n1pn2/8/3NPP2/2N1B3/PPP1B1PP/R2Q1RK1 b kq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'r1b1k2r/1pqpbppp/p1n1pn2/8/3NPP2/2N1B3/PPP1B1PP/R2Q1RK1 b kq -', 'toKey' => 'r1b1k2r/1pq1bppp/p1nppn2/8/3NPP2/2N1B3/PPP1B1PP/R2Q1RK1 w kq -'],
        ['uci' => 'd1e1', 'san' => 'Qe1', 'fromKey' => 'r1b1k2r/1pq1bppp/p1nppn2/8/3NPP2/2N1B3/PPP1B1PP/R2Q1RK1 w kq -', 'toKey' => 'r1b1k2r/1pq1bppp/p1nppn2/8/3NPP2/2N1B3/PPP1B1PP/R3QRK1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1b1k2r/1pq1bppp/p1nppn2/8/3NPP2/2N1B3/PPP1B1PP/R3QRK1 b kq -', 'toKey' => 'r1b2rk1/1pq1bppp/p1nppn2/8/3NPP2/2N1B3/PPP1B1PP/R3QRK1 w - -'],
    ];

    public const TAIMANOV_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/2p5/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f3d4', 'san' => 'Nxd4', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pp1p1ppp/4p3/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd4b5', 'san' => 'Nb5', 'fromKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/8/3NP3/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/1N6/4P3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'r1bqkbnr/pp1p1ppp/2n1p3/1N6/4P3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2npp3/1N6/4P3/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'r1bqkbnr/pp3ppp/2npp3/1N6/4P3/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pp3ppp/2npp3/1N6/2P1P3/8/PP3PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pp3ppp/2npp3/1N6/2P1P3/8/PP3PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/1N6/2P1P3/8/PP3PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'N1c3', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/1N6/2P1P3/8/PP3PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2nppn2/1N6/2P1P3/2N5/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'r1bqkb1r/pp3ppp/2nppn2/1N6/2P1P3/2N5/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/1p3ppp/p1nppn2/1N6/2P1P3/2N5/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'b5a3', 'san' => 'Na3', 'fromKey' => 'r1bqkb1r/1p3ppp/p1nppn2/1N6/2P1P3/2N5/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/1p3ppp/p1nppn2/8/2P1P3/N1N5/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'r1bqkb1r/1p3ppp/p1nppn2/8/2P1P3/N1N5/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqk2r/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'r1bqk2r/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqk2r/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQK2R b KQkq -', 'toKey' => 'r1bq1rk1/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bq1rk1/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQK2R w KQ -', 'toKey' => 'r1bq1rk1/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQ1RK1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r1bq1rk1/1p2bppp/p1nppn2/8/2P1P3/N1N5/PP2BPPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/4bppp/ppnppn2/8/2P1P3/N1N5/PP2BPPP/R1BQ1RK1 w - -'],
    ];

    public const ALAPIN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c3', 'san' => 'c3', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/2P5/PP1P1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pp1ppppp/8/2p5/4P3/2P5/PP1P1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/8/2pp4/4P3/2P5/PP1P1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'e4d5', 'san' => 'exd5', 'fromKey' => 'rnbqkbnr/pp2pppp/8/2pp4/4P3/2P5/PP1P1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/8/2pP4/8/2P5/PP1P1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd8d5', 'san' => 'Qxd5', 'fromKey' => 'rnbqkbnr/pp2pppp/8/2pP4/8/2P5/PP1P1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnb1kbnr/pp2pppp/8/2pq4/8/2P5/PP1P1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnb1kbnr/pp2pppp/8/2pq4/8/2P5/PP1P1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnb1kbnr/pp2pppp/8/2pq4/3P4/2P5/PP3PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnb1kbnr/pp2pppp/8/2pq4/3P4/2P5/PP3PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnb1kbnr/pp2pppp/8/3q4/3p4/2P5/PP3PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c3d4', 'san' => 'cxd4', 'fromKey' => 'rnb1kbnr/pp2pppp/8/3q4/3p4/2P5/PP3PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnb1kbnr/pp2pppp/8/3q4/3P4/8/PP3PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnb1kbnr/pp2pppp/8/3q4/3P4/8/PP3PPP/RNBQKBNR b KQkq -', 'toKey' => 'r1b1kbnr/pp2pppp/2n5/3q4/3P4/8/PP3PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'r1b1kbnr/pp2pppp/2n5/3q4/3P4/8/PP3PPP/RNBQKBNR w KQkq -', 'toKey' => 'r1b1kbnr/pp2pppp/2n5/3q4/3P4/5N2/PP3PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c8g4', 'san' => 'Bg4', 'fromKey' => 'r1b1kbnr/pp2pppp/2n5/3q4/3P4/5N2/PP3PPP/RNBQKB1R b KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P2b1/5N2/PP3PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P2b1/5N2/PP3PPP/RNBQKB1R w KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P2b1/2N2N2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'g4f3', 'san' => 'Bxf3', 'fromKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P2b1/2N2N2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P4/2N2b2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'g2f3', 'san' => 'gxf3', 'fromKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P4/2N2b2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P4/2N2P2/PP3P1P/R1BQKB1R b KQkq -'],
        ['uci' => 'd5d4', 'san' => 'Qxd4', 'fromKey' => 'r3kbnr/pp2pppp/2n5/3q4/3P4/2N2P2/PP3P1P/R1BQKB1R b KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/8/3q4/2N2P2/PP3P1P/R1BQKB1R w KQkq -'],
        ['uci' => 'd1d4', 'san' => 'Qxd4', 'fromKey' => 'r3kbnr/pp2pppp/2n5/8/3q4/2N2P2/PP3P1P/R1BQKB1R w KQkq -', 'toKey' => 'r3kbnr/pp2pppp/2n5/8/3Q4/2N2P2/PP3P1P/R1B1KB1R b KQkq -'],
        ['uci' => 'c6d4', 'san' => 'Nxd4', 'fromKey' => 'r3kbnr/pp2pppp/2n5/8/3Q4/2N2P2/PP3P1P/R1B1KB1R b KQkq -', 'toKey' => 'r3kbnr/pp2pppp/8/8/3n4/2N2P2/PP3P1P/R1B1KB1R w KQkq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Sicilian Defense revision 2: add Richter-Rauzer, Sozin, Scheveningen, Taimanov and Alapin complete named lines';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. e4 with ...c5. You fight for d4 from the side and usually trade your c-pawn for White\'s d-pawn, gaining an extra central pawn and a half-open c-file while White gets quicker development. Eight complete named lines: the Najdorf against Bg5, f4 and Qf3; the Dragon against the Yugoslav Attack; the Sveshnikov with ...e5; the Classical ...Nc6 and ...d6 against the Richter-Rauzer and the Sozin; the Scheveningen pawns on d6 and e6; the Taimanov against White\'s c4 bind; and the Alapin, 2. c3, met by ...d5. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['richter-rauzer', 'Richter-Rauzer: ...Be7, ...Nxd4 and ...b5 against queenside castling', 'Ten Black moves. Meet the Richter-Rauzer Attack with ...e6 and ...Be7, exchange knights on d4 and start queenside play with ...a6 and ...b5 while White\'s king sits on c1. Source: Richter-Rauzer Variation, Classical Variation, Kantscher Line.', 3, self::RAUZER_SOURCE, self::RAUZER_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Sicilian Defense is a reply to this first move.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Fight for d4 from the side instead of mirroring White with ...e5. Your c-pawn controls d4 and b4 and makes the position unbalanced from the first move.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, adds control of d4 and prepares to open the centre with d4.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White\'s e-pawn cannot easily advance and push away a knight on f6 later. The move also opens a diagonal for your bishop on c8.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White opens the centre and offers to exchange the d-pawn for your c-pawn.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange your c-pawn for White\'s d-pawn. This is the central Sicilian trade: you keep two central pawns against White\'s one, and the c-file is now half-open for your rooks and queen.'],
                ['Nxd4', 'Move your knight from f3 to d4, capturing the black pawn.', 'White recaptures with a centralised knight. White leads in development, which is the price you pay for your central pawn majority.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight and attack the pawn on e4. Nothing defends that pawn yet, so White has to spend a move on it.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White defends e4 and develops another piece.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'This is the Classical Sicilian: develop your second knight before deciding where your bishops go. It attacks the knight on d4, which only the queen defends, and adds control of e5.'],
                ['Bg5', 'Move your bishop from c1 to g5.', 'This is the Richter-Rauzer Attack. White develops the bishop with an attack on your knight on f6. If White ever takes on f6, whichever pawn recaptures leaves you with doubled f-pawns.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Control d5 and open the diagonal of the bishop on f8. Your knight on f6 is now pinned against your queen on d8, so your next job is to put the bishop on e7.'],
                ['Qd2', 'Move your queen from d1 to d2.', 'White clears d1; with b1 and c1 already empty, queenside castling is ready. The queen also defends the knight on d4 and the bishop on g5.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Put the bishop between the knight and the queen. This ends the pin, so your knight on f6 is free to move again, and prepares to castle kingside.'],
                ['O-O-O', 'Castle queenside: move your king from e1 to c1. The rook moves from a1 to d1 automatically.', 'White castles queenside, so the kings are heading for opposite wings. The rook on d1 now stands behind the queen and the knight on the d-file, which points at your pawn on d6; your bishop on e7 and queen on d8 defend it.'],
                ['Nxd4', 'Move your knight from c6 to d4, capturing the white knight.', 'Exchange knights before castling. White has to recapture with the queen, and the knight that could have jumped to b5 or f5 is gone. With fewer pieces left, you can start queenside play straight away.'],
                ['Qxd4', 'Move your queen from d2 to d4, capturing the black knight.', 'White recaptures and centralises the queen. It now attacks your pawn on d6, with the rook on d1 behind it, and adds a second attacker to your knight on f6. Both are still defended enough: d6 by your queen and bishop, f6 by your bishop and the g-pawn.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Prepare ...b5. The pawn also covers b5, so White\'s knight cannot safely jump there to attack d6, and White\'s bishop cannot safely give check from b5.'],
                ['f4', 'Move your pawn from f2 to f4.', 'White adds the f-pawn to the centre. It defends the bishop on g5 and prepares e5, a pawn push that would attack both your pawn on d6 and your knight on f6.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Gain queenside space. The pawn can go on to b4 to drive the knight from c3, where it defends e4, and it clears b7 for your bishop. For now the knight on c3 and the bishop on f1 attack b5 and only a6 defends it. The named Kantscher Line ends here: your next tasks are to develop the bishop to b7, watch b5 and White\'s e5 push, and choose when your king can castle.'],
            ]],
            ['sozin-sherbakov', 'Sozin Attack: ...e6, ...Na5 and ...b6 against the bishop on b3', 'Ten Black moves. Blunt White\'s bishop on c4 with ...e6, develop and castle, then challenge the bishop with ...Na5 and support the knight with ...b6. Source: Sozin Attack, Main Line, Sherbakov Variation.', 4, self::SOZIN_SOURCE, self::SOZIN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Sicilian Defense is a reply to this first move.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Fight for d4 from the side instead of mirroring White with ...e5. Your c-pawn controls d4 and b4 and makes the position unbalanced from the first move.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, adds control of d4 and prepares to open the centre with d4.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Control e5, so White\'s e-pawn cannot easily advance and push away a knight on f6 later. The move also opens a diagonal for your bishop on c8.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White opens the centre and offers to exchange the d-pawn for your c-pawn.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange your c-pawn for White\'s d-pawn. This is the central Sicilian trade: you keep two central pawns against White\'s one, and the c-file is now half-open for your rooks and queen.'],
                ['Nxd4', 'Move your knight from f3 to d4, capturing the black pawn.', 'White recaptures with a centralised knight. White leads in development, which is the price you pay for your central pawn majority.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight and attack the pawn on e4. Nothing defends that pawn yet, so White has to spend a move on it.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White defends e4 and develops another piece.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'This is the Classical Sicilian: develop your second knight before deciding where your bishops go. It attacks the knight on d4, which only the queen defends, and adds control of e5.'],
                ['Bc4', 'Move your bishop from f1 to c4.', 'This is the Sozin Attack. White\'s bishop aims at f7, which only your king defends, and controls d5. It is the piece White will use against your kingside.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Put a pawn in the bishop\'s way: the diagonal to f7 is now closed. The pawn on e6 also controls d5 and opens the diagonal of the bishop on f8. The bishop and the knight on d4 attack e6, but your bishop on c8 and the f7 pawn defend it.'],
                ['Be3', 'Move your bishop from c1 to e3.', 'White develops the bishop and gives the knight on d4 a second defender.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop and prepare to castle. On e7 it defends the knight on f6 and the pawn on d6.'],
                ['Bb3', 'Move your bishop from c4 to b3.', 'White moves the bishop away from c4, where ...Na5 or ...d5 could have attacked it with gain of time. On b3 it stays on the same diagonal, still pointing at e6, and the pawns on a2 and c2 protect it.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle and bring your king to safety. Of your minor pieces, only the bishop on c8 still waits to be developed.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles kingside as well. The rook on f1 is ready to support the f-pawn, which White intends to push towards your e6 pawn.'],
                ['Na5', 'Move your knight from c6 to a5.', 'Attack the bishop on b3. It is the piece that points at e6 and your king, so you are ready to exchange it with ...Nxb3. The pawns on a2 and c2 and the knight on d4 defend the bishop, so this is an offer to trade, not a threat to win it.'],
                ['f4', 'Move your pawn from f2 to f4.', 'White pushes the f-pawn. It prepares f5 to attack your e6 pawn, or e5 to drive your knight away from f6.'],
                ['b6', 'Move your pawn from b7 to b6.', 'This is the Sherbakov Variation. The pawn defends your knight on a5 and clears b7 for your bishop, which will press on e4 along the long diagonal. The named Sherbakov Variation ends here: your next tasks are to develop the bishop to b7, choose when to take the bishop on b3, and meet f5 or e5 in the centre.'],
            ]],
            ['scheveningen-classical', 'Scheveningen: ...d6 and ...e6 against Be2, f4 and Qe1', 'Ten Black moves. Use the flexible ...Nc6, ...Qc7 and ...a6 move order, then build the Scheveningen pawn pair with ...d6 and castle against White\'s Be2, f4 and Qe1 setup. Source: Scheveningen Variation, Classical Main Line.', 5, self::SCHEVENINGEN_SOURCE, self::SCHEVENINGEN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Sicilian Defense is a reply to this first move.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Fight for d4 from the side instead of mirroring White with ...e5. Your c-pawn controls d4 and b4 and makes the position unbalanced from the first move.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, adds control of d4 and prepares to open the centre with d4.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Control d5 and open the diagonal of the bishop on f8. Unlike ...d6, this keeps your d-pawn at home for now, so you can decide later how far it goes.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White opens the centre and offers to exchange the d-pawn for your c-pawn.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange your c-pawn for White\'s d-pawn. You keep two central pawns, on d7 and e6, against White\'s e-pawn, and the c-file is now half-open for your rooks and queen.'],
                ['Nxd4', 'Move your knight from f3 to d4, capturing the black pawn.', 'White recaptures with a centralised knight, which now eyes your pawn on e6. The pawns on d7 and f7 defend it.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and attack White\'s knight on d4, which only the queen defends. You have not yet committed your other knight or your d-pawn, so you can adapt to White\'s setup.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the second knight. It defends e4 and covers d5 and b5.'],
                ['Qc7', 'Move your queen from d8 to c7.', 'Put your queen on the half-open c-file. From c7 it watches e5, so White\'s e-pawn cannot easily advance, and it looks down the diagonal towards h2.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop quietly and prepares to castle kingside. This is the Classical setup: castle, put the other bishop on e3 and then gain space with f4.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Take b5 away from White\'s knights. A knight on b5 would attack your queen on c7 and the d6 square. The pawn also prepares ...b5 later.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles kingside. The rook on f1 is ready to support the f-pawn.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the kingside knight and attack e4, which the knight on c3 defends. With g8 cleared, only the bishop on f8 still stands between your king and castling.'],
                ['Be3', 'Move your bishop from c1 to e3.', 'White develops the last minor piece. On e3 the bishop defends the knight on d4 and will support a pawn on f4.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop and prepare to castle. On e7 it defends the knight on f6, and it will also defend d6 once your d-pawn arrives there.'],
                ['f4', 'Move your pawn from f2 to f4.', 'White gains space on the kingside. The f-pawn controls e5 and g5 and prepares e5 to drive your knight away, or f5 against e6. Your queen attacks f4, but the bishop on e3 and the rook on f1 defend it.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Complete the Scheveningen pawn pair on d6 and e6. Together they control c5, d5, e5 and f5, so an e5 push now meets your d-pawn. This compact small centre keeps the breaks ...e5 and ...d5 for later.'],
                ['Qe1', 'Move your queen from d1 to e1.', 'White moves the queen to e1, from where it can reach g3 or h4 along the e1-h4 diagonal and join a kingside attack. The knight on d4 now has only the bishop on e3 to defend it.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle and finish your kingside development. The named Classical Main Line ends here: your next tasks are to bring the bishop on c8 into play, for example with ...b5 and ...Bb7, and to meet White\'s kingside plans such as Qg3, e5 or f5.'],
            ]],
            ['taimanov-modern', 'Taimanov: ...d6, ...a6 and ...b6 against the c4 bind', 'Ten Black moves. Stop Nd6+ with ...d6, meet White\'s c4 bind by chasing the knight with ...a6, castle and set up a Hedgehog with ...b6. Source: Taimanov Variation, Modern Line.', 6, self::TAIMANOV_SOURCE, self::TAIMANOV_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Sicilian Defense is a reply to this first move.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Fight for d4 from the side instead of mirroring White with ...e5. Your c-pawn controls d4 and b4 and makes the position unbalanced from the first move.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, adds control of d4 and prepares to open the centre with d4.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Control d5 and open the diagonal of the bishop on f8. Unlike ...d6, this keeps your d-pawn at home for now, so you can decide later how far it goes.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White opens the centre and offers to exchange the d-pawn for your c-pawn.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange your c-pawn for White\'s d-pawn. You keep two central pawns, on d7 and e6, against White\'s e-pawn, and the c-file is now half-open for your rooks and queen.'],
                ['Nxd4', 'Move your knight from f3 to d4, capturing the black pawn.', 'White recaptures with a centralised knight, which now eyes your pawn on e6. The pawns on d7 and f7 defend it.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and attack White\'s knight on d4, which only the queen defends. You have not yet committed your other knight or your d-pawn, so you can adapt to White\'s setup.'],
                ['Nb5', 'Move your knight from d4 to b5.', 'White avoids the exchange on d4 and jumps to b5. From there the knight eyes d6: Nd6+ would give check, and after ...Bxd6 Qxd6 you would have given up your dark-squared bishop and White\'s queen would stand deep in your position.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Take d6 away from the knight. The pawn is attacked by the knight and by the queen along the d-file, but your queen and the bishop on f8 defend it.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White sets up the Maroczy Bind: pawns on c4 and e4 both control d5, which makes your freeing ...d5 hard to achieve. The c4 pawn also supports the knight on b5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight and attack e4, which has no defender at the moment, so White must spend a move protecting it.'],
                ['N1c3', 'Move your knight from b1 to c3.', 'White develops the other knight to c3, where it defends e4 and also supports the knight on b5.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Attack the knight on b5 with a pawn. It is defended, but if it stayed you would win it for a pawn with ...axb5, so White has to move it again.'],
                ['Na3', 'Move your knight from b5 to a3.', 'White retreats the knight to a3, where it defends the c4 pawn. It can return to play later through c2, but for now it stands on the edge of the board.'],
                ['Be7', 'Move your bishop from f8 to e7.', 'Develop the bishop and prepare to castle. On e7 it defends your pawn on d6 and your knight on f6.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'White develops the bishop and prepares to castle. On e2 it also defends the c4 pawn.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle your king to safety. White has more space, while your pawns on a6, d6 and e6 control b5, c5, d5, e5 and f5; you wait for the right moment to break with ...b5 or ...d5.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles too. The bishop on c1 and the knight on a3 still need good squares.'],
                ['b6', 'Move your pawn from b7 to b6.', 'Clear b7 for your bishop, which will aim at e4 along the long diagonal once the knight on c6 moves. With pawns on a6, b6, d6 and e6 you have a Hedgehog setup. The named Modern Line ends here: your next tasks are to develop the bishop to b7, bring a rook to the c-file and prepare ...b5 or ...d5 to challenge White\'s grip on the centre.'],
            ]],
            ['alapin-endgame', 'Alapin Variation: 2...d5, ...Bg4 and an early queen trade', 'Nine Black moves. Answer 2. c3 with ...d5, recapture with the queen, pin with ...Bg4, give up the bishop for the knight and win the d4 pawn as the queens come off. Source: Alapin Variation, Barmen Defense, Endgame Variation.', 7, self::ALAPIN_SOURCE, self::ALAPIN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens lines for the queen and the bishop on f1. The Sicilian Defense is a reply to this first move.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Fight for d4 from the side instead of mirroring White with ...e5. Your c-pawn controls d4 and b4 and makes the position unbalanced from the first move.'],
                ['c3', 'Move your pawn from c2 to c3.', 'This is the Alapin Variation. White prepares d4, so that after ...cxd4 a pawn can recapture and keep a pawn in the centre. It sidesteps the Open Sicilian lines of this course.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Strike at the centre at once. Your d-pawn attacks e4, which nothing defends, before White can build a full centre with d4. White\'s pawn on c3 also occupies the square White\'s knight would like, which you will use in a moment.'],
                ['exd5', 'Move your pawn from e4 to d5, capturing the black pawn.', 'White exchanges on d5 rather than let you take on e4.'],
                ['Qxd5', 'Move your queen from d8 to d5, capturing the white pawn.', 'Recapture with the queen. An early queen in the centre can often be chased by a knight coming to c3, but here White\'s own pawn occupies c3. From d5 your queen eyes the pawns on a2 and g2.'],
                ['d4', 'Move your pawn from d2 to d4.', 'White builds the centre with d4. Your c-pawn and queen attack it; the pawn on c3 and the queen on d1 defend it.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange on d4. When White recaptures with the c-pawn, White\'s d-pawn will be isolated: no white pawn will be left on the c-file or the e-file to support it.'],
                ['cxd4', 'Move your pawn from c3 to d4, capturing the black pawn.', 'White recaptures. The d4 pawn is isolated, but the c3 square is free again, so White\'s knight can come there and attack your queen.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight and add pressure on d4. Your knight and queen attack the pawn, and only White\'s queen defends it.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight and defends d4 a second time. The pawn is now attacked twice and defended twice.'],
                ['Bg4', 'Move your bishop from c8 to g4.', 'Pin the knight on f3 against White\'s queen. That knight is one of the two defenders of d4, so you are preparing to remove it with ...Bxf3.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops with an attack on your queen, which has no defender. White does not defend d4 again, which gives you the chance to win that pawn.'],
                ['Bxf3', 'Move your bishop from g4 to f3, capturing the white knight.', 'Take the knight, one of the two defenders of d4. Your bishop on f3 now attacks the queen on d1 and defends your own queen on d5.'],
                ['gxf3', 'Move your pawn from g2 to f3, capturing the black bishop.', 'White recaptures with the g-pawn and keeps the queen on d1, where it still guards d4. The price is a damaged pawn structure: doubled f-pawns and an isolated h-pawn. Your queen is still attacked by the knight and no longer defended, so it has to move.'],
                ['Qxd4', 'Move your queen from d5 to d4, capturing the white pawn.', 'Move the queen by taking the d4 pawn, which only White\'s queen defended. The knight on c6 protects your queen, which now attacks the knight on c3, the pawn on f2 and the white queen.'],
                ['Qxd4', 'Move your queen from d1 to d4, capturing the black queen.', 'White exchanges queens. In return for the pawn, White keeps the bishop pair and will try to use the open lines and the active knight on c3.'],
                ['Nxd4', 'Move your knight from c6 to d4, capturing the white queen.', 'Recapture: you are a pawn up and White has the two bishops. Your knight threatens ...Nc2+, a check that also hits the rook on a1, and attacks the pawn on f3. White\'s knight can jump to b5 to attack your knight and threaten Nc7+, forking your king and rook. The named Endgame Variation ends here: your next tasks are to parry such knight jumps, develop your kingside pieces and keep the extra pawn.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='sicilian'")) {
            throw new \RuntimeException('Sicilian Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='sicilian' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Sicilian Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Sicilian Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='sicilian' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='sicilian' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='sicilian' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('sicilian',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('sicilian',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Sicilian Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='sicilian'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Sicilian Defense sessions and progress.');
    }
}
