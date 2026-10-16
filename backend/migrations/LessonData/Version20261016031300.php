<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** New Two Knights Defense course: complete named pinned paths with original teaching notes. */
final class Version20261016031300 extends AbstractMigration
{
    public const GORING_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Nf6 4. Ng5 d5 5. exd5 Na5 6. Bb5+ c6 7. dxc6 bxc6 8. Be2 h6 9. Nf3 e4 10. Ne5 Qc7';
    public const YANKOVICH_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Nf6 4. Ng5 d5 5. exd5 Na5 6. d3 h6 7. Nf3 e4 8. Qe2 Nxc4 9. dxc4 Bc5 10. Nfd2';
    public const BERGER_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Nf6 4. d4 exd4 5. O-O Bc5 6. e5 d5 7. exf6 dxc4 8. Re1+ Be6 9. Ng5 Qd5 10. Nc3 Qf5 11. g4 Qg6 12. Nce4 Bb6 13. f4 O-O-O';
    public const RUBINSTEIN_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Nf6 4. d4 exd4 5. O-O Bc5 6. e5 d5 7. exf6 dxc4 8. Re1+ Be6 9. Ng5 Qd5 10. Nc3 Qf5 11. Nce4 Bf8';

    public const GORING_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'f3g5', 'san' => 'Ng5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p1N1/2B1P3/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p1N1/2B1P3/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3pp1N1/2B1P3/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e4d5', 'san' => 'exd5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3pp1N1/2B1P3/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3Pp1N1/2B5/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c6a5', 'san' => 'Na5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3Pp1N1/2B5/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'c4b5', 'san' => 'Bb5+', 'fromKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/5n2/nB1Pp1N1/8/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'r1bqkb1r/ppp2ppp/5n2/nB1Pp1N1/8/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2p2n2/nB1Pp1N1/8/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd5c6', 'san' => 'dxc6', 'fromKey' => 'r1bqkb1r/pp3ppp/2p2n2/nB1Pp1N1/8/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pp3ppp/2P2n2/nB2p1N1/8/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'b7c6', 'san' => 'bxc6', 'fromKey' => 'r1bqkb1r/pp3ppp/2P2n2/nB2p1N1/8/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/p4ppp/2p2n2/nB2p1N1/8/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b5e2', 'san' => 'Be2', 'fromKey' => 'r1bqkb1r/p4ppp/2p2n2/nB2p1N1/8/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/p4ppp/2p2n2/n3p1N1/8/8/PPPPBPPP/RNBQK2R b KQkq -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'r1bqkb1r/p4ppp/2p2n2/n3p1N1/8/8/PPPPBPPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3p1N1/8/8/PPPPBPPP/RNBQK2R w KQkq -'],
        ['uci' => 'g5f3', 'san' => 'Nf3', 'fromKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3p1N1/8/8/PPPPBPPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3p3/8/5N2/PPPPBPPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5e4', 'san' => 'e4', 'fromKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3p3/8/5N2/PPPPBPPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/p4pp1/2p2n1p/n7/4p3/5N2/PPPPBPPP/RNBQK2R w KQkq -'],
        ['uci' => 'f3e5', 'san' => 'Ne5', 'fromKey' => 'r1bqkb1r/p4pp1/2p2n1p/n7/4p3/5N2/PPPPBPPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3N3/4p3/8/PPPPBPPP/RNBQK2R b KQkq -'],
        ['uci' => 'd8c7', 'san' => 'Qc7', 'fromKey' => 'r1bqkb1r/p4pp1/2p2n1p/n3N3/4p3/8/PPPPBPPP/RNBQK2R b KQkq -', 'toKey' => 'r1b1kb1r/p1q2pp1/2p2n1p/n3N3/4p3/8/PPPPBPPP/RNBQK2R w KQkq -'],
    ];

    public const YANKOVICH_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'f3g5', 'san' => 'Ng5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p1N1/2B1P3/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p1N1/2B1P3/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3pp1N1/2B1P3/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e4d5', 'san' => 'exd5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3pp1N1/2B1P3/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3Pp1N1/2B5/8/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c6a5', 'san' => 'Na5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n2n2/3Pp1N1/2B5/8/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/8/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d3', 'san' => 'd3', 'fromKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/8/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/3P4/PPP2PPP/RNBQK2R b KQkq -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'r1bqkb1r/ppp2ppp/5n2/n2Pp1N1/2B5/3P4/PPP2PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2Pp1N1/2B5/3P4/PPP2PPP/RNBQK2R w KQkq -'],
        ['uci' => 'g5f3', 'san' => 'Nf3', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2Pp1N1/2B5/3P4/PPP2PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2Pp3/2B5/3P1N2/PPP2PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5e4', 'san' => 'e4', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2Pp3/2B5/3P1N2/PPP2PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2P4/2B1p3/3P1N2/PPP2PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd1e2', 'san' => 'Qe2', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2P4/2B1p3/3P1N2/PPP2PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2P4/2B1p3/3P1N2/PPP1QPPP/RNB1K2R b KQkq -'],
        ['uci' => 'a5c4', 'san' => 'Nxc4', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/n2P4/2B1p3/3P1N2/PPP1QPPP/RNB1K2R b KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/3P4/2n1p3/3P1N2/PPP1QPPP/RNB1K2R w KQkq -'],
        ['uci' => 'd3c4', 'san' => 'dxc4', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/3P4/2n1p3/3P1N2/PPP1QPPP/RNB1K2R w KQkq -', 'toKey' => 'r1bqkb1r/ppp2pp1/5n1p/3P4/2P1p3/5N2/PPP1QPPP/RNB1K2R b KQkq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkb1r/ppp2pp1/5n1p/3P4/2P1p3/5N2/PPP1QPPP/RNB1K2R b KQkq -', 'toKey' => 'r1bqk2r/ppp2pp1/5n1p/2bP4/2P1p3/5N2/PPP1QPPP/RNB1K2R w KQkq -'],
        ['uci' => 'f3d2', 'san' => 'Nfd2', 'fromKey' => 'r1bqk2r/ppp2pp1/5n1p/2bP4/2P1p3/5N2/PPP1QPPP/RNB1K2R w KQkq -', 'toKey' => 'r1bqk2r/ppp2pp1/5n1p/2bP4/2P1p3/8/PPPNQPPP/RNB1K2R b KQkq -'],
    ];

    public const BERGER_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b5/2BpP3/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b5/2BpP3/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b1P3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b1P3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2n2/2bpP3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 w kq d6'],
        ['uci' => 'e5f6', 'san' => 'exf6', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2n2/2bpP3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 w kq d6', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2bp4/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2bp4/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'f1e1', 'san' => 'Re1+', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'f3g5', 'san' => 'Ng5', 'fromKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b3N1/2pp4/8/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'd8d5', 'san' => 'Qd5', 'fromKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b3N1/2pp4/8/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/8/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/8/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/2N5/PPP2PPP/R1BQR1K1 b kq -'],
        ['uci' => 'd5f5', 'san' => 'Qf5', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/2N5/PPP2PPP/R1BQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp4/2N5/PPP2PPP/R1BQR1K1 w kq -'],
        ['uci' => 'g2g4', 'san' => 'g4', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp4/2N5/PPP2PPP/R1BQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp2P1/2N5/PPP2P1P/R1BQR1K1 b kq -'],
        ['uci' => 'f5g6', 'san' => 'Qg6', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp2P1/2N5/PPP2P1P/R1BQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bPq1/2b3N1/2pp2P1/2N5/PPP2P1P/R1BQR1K1 w kq -'],
        ['uci' => 'c3e4', 'san' => 'Nce4', 'fromKey' => 'r3k2r/ppp2ppp/2n1bPq1/2b3N1/2pp2P1/2N5/PPP2P1P/R1BQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bPq1/2b3N1/2ppN1P1/8/PPP2P1P/R1BQR1K1 b kq -'],
        ['uci' => 'c5b6', 'san' => 'Bb6', 'fromKey' => 'r3k2r/ppp2ppp/2n1bPq1/2b3N1/2ppN1P1/8/PPP2P1P/R1BQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/1bn1bPq1/6N1/2ppN1P1/8/PPP2P1P/R1BQR1K1 w kq -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'r3k2r/ppp2ppp/1bn1bPq1/6N1/2ppN1P1/8/PPP2P1P/R1BQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/1bn1bPq1/6N1/2ppNPP1/8/PPP4P/R1BQR1K1 b kq -'],
        ['uci' => 'e8c8', 'san' => 'O-O-O', 'fromKey' => 'r3k2r/ppp2ppp/1bn1bPq1/6N1/2ppNPP1/8/PPP4P/R1BQR1K1 b kq -', 'toKey' => '2kr3r/ppp2ppp/1bn1bPq1/6N1/2ppNPP1/8/PPP4P/R1BQR1K1 w - -'],
    ];

    public const RUBINSTEIN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b5/2BpP3/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b5/2BpP3/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b1P3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqk2r/pppp1ppp/2n2n2/2b1P3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2n2/2bpP3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 w kq d6'],
        ['uci' => 'e5f6', 'san' => 'exf6', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2n2/2bpP3/2Bp4/5N2/PPP2PPP/RNBQ1RK1 w kq d6', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2bp4/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2bp4/2Bp4/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'f1e1', 'san' => 'Re1+', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1bqk2r/ppp2ppp/2n2P2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'f3g5', 'san' => 'Ng5', 'fromKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b5/2pp4/5N2/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b3N1/2pp4/8/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'd8d5', 'san' => 'Qd5', 'fromKey' => 'r2qk2r/ppp2ppp/2n1bP2/2b3N1/2pp4/8/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/8/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/8/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/2N5/PPP2PPP/R1BQR1K1 b kq -'],
        ['uci' => 'd5f5', 'san' => 'Qf5', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2bq2N1/2pp4/2N5/PPP2PPP/R1BQR1K1 b kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp4/2N5/PPP2PPP/R1BQR1K1 w kq -'],
        ['uci' => 'c3e4', 'san' => 'Nce4', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2pp4/2N5/PPP2PPP/R1BQR1K1 w kq -', 'toKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2ppN3/8/PPP2PPP/R1BQR1K1 b kq -'],
        ['uci' => 'c5f8', 'san' => 'Bf8', 'fromKey' => 'r3k2r/ppp2ppp/2n1bP2/2b2qN1/2ppN3/8/PPP2PPP/R1BQR1K1 b kq -', 'toKey' => 'r3kb1r/ppp2ppp/2n1bP2/5qN1/2ppN3/8/PPP2PPP/R1BQR1K1 w kq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish the Two Knights Defense course: Polerio Göring and Yankovich, Max Lange Berger and Rubinstein';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. e4 e5 2. Nf3 Nc6 3. Bc4. With 3...Nf6, the Two Knights Defense, you attack e4 at once and choose active play over a slow build-up. Against 4. Ng5 you block with ...d5 and answer exd5 with ...Na5, giving up a pawn for time: the Göring line meets 6. Bb5+ and ends with ...Qc7 against a knight on e5, and the Yankovich line meets 6. d3 with ...e4 and ...Nxc4. Against 4. d4 you take, develop with ...Bc5 and enter the Max Lange Attack, where you keep an extra pawn: the Berger line meets 11. g4 and castles queenside, and the Rubinstein line meets 11. Nce4 with ...Bf8. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['polerio-goring', 'Polerio Defense: ...Na5, the knight chase and ...Qc7', "Ten Black moves. Meet Ng5 with ...d5 and ...Na5, give up a pawn, chase White's bishop and knight with ...c6, ...h6 and ...e4, then attack the knight on e5 with ...Qc7. Source: Two Knights Defense, Polerio Defense, Göring Variation.", self::GORING_SOURCE, self::GORING_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens diagonals for the queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', "Claim your share of the centre and stop White's e-pawn from advancing further."],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops with a threat: the knight attacks your pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Defend e5 by developing a knight towards the centre.'],
                ['Bc4', 'Move your bishop from f1 to c4.', "White's bishop aims at f7, the square next to your king that only the king defends."],
                ['Nf6', 'Move your knight from g8 to f6.', "This is the Two Knights Defense. Instead of ...Bc5, develop the other knight and attack White's pawn on e4, which nothing defends. You choose active counterplay over a slow build-up."],
                ['Ng5', 'Move your knight from f3 to g5.', 'White attacks f7 a second time, together with the bishop on c4. Only your king defends that square, so you must react at once.'],
                ['d5', 'Move your pawn from d7 to d5.', "Block the bishop's diagonal towards f7 with a pawn. The pawn also attacks the bishop on c4 and the pawn on e4, so White has to respond."],
                ['exd5', 'Move your pawn from e4 to d5, capturing the black pawn.', 'White takes on d5, and the white pawn there now attacks your knight on c6. You are a pawn down.'],
                ['Na5', 'Move your knight from c6 to a5.', 'This is the Polerio Defense. Recapturing with ...Nxd5 would allow sharp attacks on f7 such as the Fried Liver Attack, 6. Nxf7. Instead, move the attacked knight to a5, where it attacks the bishop on c4. You stay a pawn down in return for time and quick development.'],
                ['Bb5+', 'Move your bishop from c4 to b5, giving check.', 'White moves the attacked bishop with check. The pawn on d5 has lost its defender and is now attacked by your queen and the knight on f6.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Block the check with a pawn that attacks the bishop on b5. The pawn also attacks d5, but it cannot take there while the bishop pins it against your king.'],
                ['dxc6', 'Move your pawn from d5 to c6, capturing the black pawn.', 'White takes the c6 pawn instead of losing the one on d5. For a moment White is two pawns ahead, and the pawn on c6 attacks b7.'],
                ['bxc6', 'Move your pawn from b7 to c6, capturing the white pawn.', 'Recapture with the b-pawn and attack the bishop on b5 again. You are one pawn down, but the b-file is now half-open for a rook, and your bishop on c8 can use the diagonal towards a6.'],
                ['Be2', 'Move your bishop from b5 to e2.', 'White retreats the bishop to e2, a safe square close to the king.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Attack the knight on g5, which nothing defends. It has already moved twice and must move again: the time White spends is your compensation for the pawn.'],
                ['Nf3', 'Move your knight from g5 to f3.', 'White retreats the knight to f3, where it attacks your pawn on e5. Nothing defends that pawn now.'],
                ['e4', 'Move your pawn from e5 to e4.', 'Push the pawn with a threat instead of defending it. It attacks the knight on f3, which must move yet again, and gains space: the pawn on e4 also controls d3.'],
                ['Ne5', 'Move your knight from f3 to e5.', 'White jumps into the centre rather than retreating. On e5 the knight attacks your pawns on c6 and f7.'],
                ['Qc7', 'Move your queen from d8 to c7.', 'This is the Göring Variation. Attack the knight on e5 with your queen; nothing defends it at the moment. The queen also guards c6, f7 and your knight on a5. The named Göring Variation ends here: White must protect or move the knight on e5, and your next tasks are to develop the bishop on f8, castle and use your active pieces as compensation for the pawn.'],
            ]],
            ['polerio-yankovich', 'Polerio Defense against 6. d3: ...e4, ...Nxc4 and ...Bc5', "Nine Black moves. Against 6. d3, chase the knight with ...h6 and ...e4, take White's bishop with ...Nxc4 and develop with ...Bc5. Source: Two Knights Defense, Polerio Defense, Yankovich Variation.", self::YANKOVICH_SOURCE, self::YANKOVICH_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens diagonals for the queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', "Claim your share of the centre and stop White's e-pawn from advancing further."],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops with a threat: the knight attacks your pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Defend e5 by developing a knight towards the centre.'],
                ['Bc4', 'Move your bishop from f1 to c4.', "White's bishop aims at f7, the square next to your king that only the king defends."],
                ['Nf6', 'Move your knight from g8 to f6.', "This is the Two Knights Defense. Instead of ...Bc5, develop the other knight and attack White's pawn on e4, which nothing defends. You choose active counterplay over a slow build-up."],
                ['Ng5', 'Move your knight from f3 to g5.', 'White attacks f7 a second time, together with the bishop on c4. Only your king defends that square, so you must react at once.'],
                ['d5', 'Move your pawn from d7 to d5.', "Block the bishop's diagonal towards f7 with a pawn. The pawn also attacks the bishop on c4 and the pawn on e4, so White has to respond."],
                ['exd5', 'Move your pawn from e4 to d5, capturing the black pawn.', 'White takes on d5, and the white pawn there now attacks your knight on c6. You are a pawn down.'],
                ['Na5', 'Move your knight from c6 to a5.', 'This is the Polerio Defense. Recapturing with ...Nxd5 would allow sharp attacks on f7 such as the Fried Liver Attack, 6. Nxf7. Instead, move the attacked knight to a5, where it attacks the bishop on c4. You stay a pawn down in return for time and quick development.'],
                ['d3', 'Move your pawn from d2 to d3.', 'White defends the bishop on c4 with a pawn instead of moving it. The pawn also opens the diagonal of the bishop on c1, which now protects the knight on g5.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Attack the knight on g5. The bishop on c1 defends it, but White does not want to give a knight for your h-pawn, so the knight has to move again.'],
                ['Nf3', 'Move your knight from g5 to f3.', 'White retreats the knight to f3, where it attacks your pawn on e5. Nothing defends that pawn now.'],
                ['e4', 'Move your pawn from e5 to e4.', 'Push the pawn with a threat instead of defending it. It attacks the knight on f3 and the pawn on d3, and gains space in the centre.'],
                ['Qe2', 'Move your queen from d1 to e2.', 'White pins your e4 pawn: the queen on e2 and your king stand on the e-file with only that pawn between them, so it cannot capture on f3 or d3 for now. The queen and the d3 pawn both attack e4, which only your knight on f6 defends.'],
                ['Nxc4', 'Move your knight from a5 to c4, capturing the white bishop.', 'Take the bishop. White can only recapture with the d3 pawn, which then no longer attacks your e4 pawn. White also loses the bishop that aimed at f7.'],
                ['dxc4', 'Move your pawn from d3 to c4, capturing the black knight.', 'White recaptures. White is still a pawn up, but now has doubled pawns on c2 and c4.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Develop the bishop actively: it aims at f2 and clears the way for castling. Your e4 pawn is still pinned against your king, and castling will break that pin.'],
                ['Nfd2', 'Move your knight from f3 to d2.', 'White moves the knight off f3 before the pin is broken, and from d2 it attacks your e4 pawn a second time. That pawn is now attacked by the knight and the queen and defended only by your knight on f6. The named Yankovich Variation ends here: your next tasks are to castle, which unpins the e4 pawn, and to defend it or use the time White spends winning it to develop your queenside pieces.'],
            ]],
            ['max-lange-berger', 'Max Lange Attack: 11. g4 and queenside castling', 'Thirteen Black moves. Against 4. d4 and 5. O-O, develop with ...Bc5, meet e5 with ...d5, hold the pinned bishop on e6 with your queen and castle queenside. Source: Two Knights Defense, Max Lange Attack, Berger Variation.', self::BERGER_SOURCE, self::BERGER_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens diagonals for the queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', "Claim your share of the centre and stop White's e-pawn from advancing further."],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops with a threat: the knight attacks your pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Defend e5 by developing a knight towards the centre.'],
                ['Bc4', 'Move your bishop from f1 to c4.', "White's bishop aims at f7, the square next to your king that only the king defends."],
                ['Nf6', 'Move your knight from g8 to f6.', "This is the Two Knights Defense. Instead of ...Bc5, develop the other knight and attack White's pawn on e4, which nothing defends. You choose active counterplay over a slow build-up."],
                ['d4', 'Move your pawn from d2 to d4.', 'Instead of attacking f7 with Ng5, White opens the centre at once. The pawn attacks your pawn on e5.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Take the pawn. Your knight on f6 still attacks e4, which nothing defends.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and leaves the pawn on e4 unprotected, preferring quick development to material.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Develop the bishop and defend your extra pawn on d4. Taking on e4 with your knight is also possible; developing with ...Bc5 allows the sharp Max Lange Attack.'],
                ['e5', 'Move your pawn from e4 to e5.', 'This is the Max Lange Attack. White advances the pawn and attacks your knight on f6.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Strike back in the centre instead of moving the knight. Your pawn attacks the bishop on c4, so each side now has a minor piece under attack.'],
                ['exf6', 'Move your pawn from e5 to f6, capturing the black knight.', 'White captures your knight on f6. The pawn now attacks g7, which nothing defends.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white bishop.', 'Take the bishop. Each side has lost a minor piece and you are a pawn up, but the white pawn on f6 is close to your king.'],
                ['Re1+', 'Move your rook from f1 to e1, giving check.', 'White checks along the open e-file.'],
                ['Be6', 'Move your bishop from c8 to e6.', 'Block the check with the bishop. On e6 it defends your pawn on c4 and the f7 square, but the rook on e1 pins it against your king.'],
                ['Ng5', 'Move your knight from f3 to g5.', 'White attacks the pinned bishop a second time. The knight also hits f7 and h7.'],
                ['Qd5', 'Move your queen from d8 to d5.', 'Defend the bishop on e6 with your queen. From d5 the queen also protects your pawns on c4 and d4 and attacks the knight on g5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops with tempo: the knight attacks your queen. You cannot take it with ...dxc3, because White would capture your queen on d5 and your pinned bishop could not recapture.'],
                ['Qf5', 'Move your queen from d5 to f5.', 'Move the queen to f5. It keeps protecting the bishop on e6, guards h7 and attacks the knight on g5 and the pawn on f6.'],
                ['g4', 'Move your pawn from g2 to g4.', "White drives your queen away with the g-pawn, which the queen on d1 defends. The cost is that the g-pawn no longer shields White's king."],
                ['Qg6', 'Move your queen from f5 to g6.', 'Keep the queen on the kingside. On g6 it defends g7 against the pawn on f6 and still attacks the knight on g5.'],
                ['Nce4', 'Move your knight from c3 to e4.', 'White brings the second knight to e4. It attacks your bishop on c5, protects the f6 pawn and the knight on g5, and blocks the e-file, so your bishop on e6 is no longer pinned.'],
                ['Bb6', 'Move your bishop from c5 to b6.', 'Save the bishop by retreating to b6, where it keeps protecting your pawn on d4.'],
                ['f4', 'Move your pawn from f2 to f4.', 'White supports the knight on g5 with a pawn and prepares f4-f5, which would attack your queen and your bishop on e6 at the same time.'],
                ['O-O-O', 'Castle queenside: move your king from e8 to c8. The rook moves from a8 to d8 automatically.', "Castle queenside, away from White's pawn on f6 and the knights on e4 and g5. Your rook arrives on d8, where it adds a defender to d4. The named Berger Variation ends here: you are a pawn up, and your next tasks are to meet the threat of f4-f5 against your queen and bishop and keep your pawns on c4 and d4 protected."],
            ]],
            ['max-lange-rubinstein', 'Max Lange Attack: 11. Nce4 and ...Bf8', 'Eleven Black moves. Meet the Max Lange Attack as in the Berger line until White plays 11. Nce4 at once; bring your bishop back to f8 to guard g7. Source: Two Knights Defense, Max Lange Attack, Rubinstein Variation.', self::RUBINSTEIN_SOURCE, self::RUBINSTEIN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens diagonals for the queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', "Claim your share of the centre and stop White's e-pawn from advancing further."],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops with a threat: the knight attacks your pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Defend e5 by developing a knight towards the centre.'],
                ['Bc4', 'Move your bishop from f1 to c4.', "White's bishop aims at f7, the square next to your king that only the king defends."],
                ['Nf6', 'Move your knight from g8 to f6.', "This is the Two Knights Defense. Instead of ...Bc5, develop the other knight and attack White's pawn on e4, which nothing defends. You choose active counterplay over a slow build-up."],
                ['d4', 'Move your pawn from d2 to d4.', 'Instead of attacking f7 with Ng5, White opens the centre at once. The pawn attacks your pawn on e5.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Take the pawn. Your knight on f6 still attacks e4, which nothing defends.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and leaves the pawn on e4 unprotected, preferring quick development to material.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Develop the bishop and defend your extra pawn on d4. Taking on e4 with your knight is also possible; developing with ...Bc5 allows the sharp Max Lange Attack.'],
                ['e5', 'Move your pawn from e4 to e5.', 'This is the Max Lange Attack. White advances the pawn and attacks your knight on f6.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Strike back in the centre instead of moving the knight. Your pawn attacks the bishop on c4, so each side now has a minor piece under attack.'],
                ['exf6', 'Move your pawn from e5 to f6, capturing the black knight.', 'White captures your knight on f6. The pawn now attacks g7, which nothing defends.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white bishop.', 'Take the bishop. Each side has lost a minor piece and you are a pawn up, but the white pawn on f6 is close to your king.'],
                ['Re1+', 'Move your rook from f1 to e1, giving check.', 'White checks along the open e-file.'],
                ['Be6', 'Move your bishop from c8 to e6.', 'Block the check with the bishop. On e6 it defends your pawn on c4 and the f7 square, but the rook on e1 pins it against your king.'],
                ['Ng5', 'Move your knight from f3 to g5.', 'White attacks the pinned bishop a second time. The knight also hits f7 and h7.'],
                ['Qd5', 'Move your queen from d8 to d5.', 'Defend the bishop on e6 with your queen. From d5 the queen also protects your pawns on c4 and d4 and attacks the knight on g5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops with tempo: the knight attacks your queen. You cannot take it with ...dxc3, because White would capture your queen on d5 and your pinned bishop could not recapture.'],
                ['Qf5', 'Move your queen from d5 to f5.', 'Move the queen to f5. It keeps protecting the bishop on e6, guards h7 and attacks the knight on g5 and the pawn on f6.'],
                ['Nce4', 'Move your knight from c3 to e4.', 'Instead of chasing your queen with g4, White brings the second knight to e4. It attacks your bishop on c5, protects the f6 pawn and the knight on g5, and blocks the e-file, so your bishop on e6 is no longer pinned.'],
                ['Bf8', 'Move your bishop from c5 to f8.', "Retreat the bishop to f8, where it defends g7 against the pawn on f6. The bishop now blocks kingside castling, but b8, c8 and d8 are empty, so queenside castling is available. The named Rubinstein Variation ends here: you are a pawn up, and your next tasks are to castle queenside, keep your pawns on c4 and d4 protected and watch White's knights on e4 and g5 near your king."],
            ]],
        ];

        if (false !== $this->connection->fetchOne("SELECT id FROM lesson WHERE id='two-knights'")) {
            throw new \RuntimeException('The Two Knights Defense course already exists.');
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Two Knights Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }
        $start = $this->connection->fetchOne('SELECT id FROM chess_position WHERE position_key=?', [self::GORING_FACTS[0]['fromKey']]);
        if (false === $start) {
            throw new \RuntimeException('The imported Two Knights Defense start position is missing.');
        }

        $this->addSql("INSERT INTO lesson (id,current_revision,sort_order) VALUES ('two-knights',NULL,21)");
        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) VALUES ('two-knights',1,?,?,?,UTC_TIMESTAMP(),'b')", ['Two Knights Defense', $introduction, (int) $start]);
        foreach ($lines as $index => [$lineId, $title, $description, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('two-knights',1,?,?,?,?,?)", [$lineId, $title, $description, $index, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('two-knights',1,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Two Knights Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=1 WHERE id='two-knights'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve Two Knights Defense sessions and progress.');
    }
}
