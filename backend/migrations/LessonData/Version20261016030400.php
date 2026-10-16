<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** New Semi-Slav Defense course: complete named pinned paths with original teaching notes. */
final class Version20261016030400 extends AbstractMigration
{
    public const WADE_SOURCE = '1. d4 d5 2. c4 c6 3. Nc3 Nf6 4. e3 e6 5. Nf3 Nbd7 6. Bd3 dxc4 7. Bxc4 b5 8. Bd3 Bb7 9. e4 b4 10. Na4 c5 11. e5 Nd5';
    public const CHIGORIN_SOURCE = '1. d4 d5 2. c4 c6 3. Nc3 Nf6 4. e3 e6 5. Nf3 Nbd7 6. Bd3 dxc4 7. Bxc4 b5 8. Bd3 Bd6 9. O-O Bb7 10. a3 O-O 11. Qc2';
    public const A6_MERAN_SOURCE = '1. d4 d5 2. c4 e6 3. Nc3 c6 4. e3 Nf6 5. Nf3 a6 6. Bd3 dxc4 7. Bxc4 b5 8. Bd3 c5 9. O-O Bb7 10. Qe2 Nbd7';
    public const BOTVINNIK_SOURCE = '1. d4 d5 2. c4 c6 3. Nf3 Nf6 4. Nc3 e6 5. Bg5 dxc4 6. e4 b5 7. e5 h6 8. Bh4 g5 9. Nxg5 hxg5 10. Bxg5 Nbd7 11. g3';
    public const ANTI_MERAN_SOURCE = '1. d4 d5 2. c4 c6 3. Nf3 Nf6 4. Nc3 e6 5. e3 Nbd7 6. Qc2 Bd6 7. e4 dxe4 8. Nxe4 Nxe4 9. Qxe4 e5 10. dxe5';

    public const WADE_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'd3c4', 'san' => 'Bxc4', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'c4d3', 'san' => 'Bd3', 'fromKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e3e4', 'san' => 'e4', 'fromKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/1p6/3PP3/2NB1N2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'b5b4', 'san' => 'b4', 'fromKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/1p6/3PP3/2NB1N2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/8/1p1PP3/2NB1N2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'c3a4', 'san' => 'Na4', 'fromKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/8/1p1PP3/2NB1N2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/8/Np1PP3/3B1N2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'c6c5', 'san' => 'c5', 'fromKey' => 'r2qkb1r/pb1n1ppp/2p1pn2/8/Np1PP3/3B1N2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/4pn2/2p5/Np1PP3/3B1N2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'r2qkb1r/pb1n1ppp/4pn2/2p5/Np1PP3/3B1N2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/4pn2/2p1P3/Np1P4/3B1N2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'f6d5', 'san' => 'Nd5', 'fromKey' => 'r2qkb1r/pb1n1ppp/4pn2/2p1P3/Np1P4/3B1N2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r2qkb1r/pb1n1ppp/4p3/2pnP3/Np1P4/3B1N2/PP3PPP/R1BQK2R w KQkq -'],
    ];

    public const CHIGORIN_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'd3c4', 'san' => 'Bxc4', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'c4d3', 'san' => 'Bd3', 'fromKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'f8d6', 'san' => 'Bd6', 'fromKey' => 'r1bqkb1r/p2n1ppp/2p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'r1bqk2r/p2n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/p2n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'r1bqk2r/p2n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'r1bqk2r/p2n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQ1RK1 b kq -', 'toKey' => 'r2qk2r/pb1n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQ1RK1 w kq -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'r2qk2r/pb1n1ppp/2pbpn2/1p6/3P4/2NBPN2/PP3PPP/R1BQ1RK1 w kq -', 'toKey' => 'r2qk2r/pb1n1ppp/2pbpn2/1p6/3P4/P1NBPN2/1P3PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r2qk2r/pb1n1ppp/2pbpn2/1p6/3P4/P1NBPN2/1P3PPP/R1BQ1RK1 b kq -', 'toKey' => 'r2q1rk1/pb1n1ppp/2pbpn2/1p6/3P4/P1NBPN2/1P3PPP/R1BQ1RK1 w - -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r2q1rk1/pb1n1ppp/2pbpn2/1p6/3P4/P1NBPN2/1P3PPP/R1BQ1RK1 w - -', 'toKey' => 'r2q1rk1/pb1n1ppp/2pbpn2/1p6/3P4/P1NBPN2/1PQ2PPP/R1B2RK1 b - -'],
    ];

    public const A6_MERAN_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/2p1p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkbnr/pp3ppp/2p1p3/3p4/2PP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp3ppp/2p1p3/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp3ppp/2p1p3/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'a7a6', 'san' => 'a6', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/3p4/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'd3c4', 'san' => 'Bxc4', 'fromKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/8/2pP4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'rnbqkb1r/1p3ppp/p1p1pn2/8/2BP4/2N1PN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rnbqkb1r/5ppp/p1p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'c4d3', 'san' => 'Bd3', 'fromKey' => 'rnbqkb1r/5ppp/p1p1pn2/1p6/2BP4/2N1PN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'rnbqkb1r/5ppp/p1p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'c6c5', 'san' => 'c5', 'fromKey' => 'rnbqkb1r/5ppp/p1p1pn2/1p6/3P4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rnbqkb1r/5ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbqkb1r/5ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'rnbqkb1r/5ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'rnbqkb1r/5ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQ1RK1 b kq -', 'toKey' => 'rn1qkb1r/1b3ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQ1RK1 w kq -'],
        ['uci' => 'd1e2', 'san' => 'Qe2', 'fromKey' => 'rn1qkb1r/1b3ppp/p3pn2/1pp5/3P4/2NBPN2/PP3PPP/R1BQ1RK1 w kq -', 'toKey' => 'rn1qkb1r/1b3ppp/p3pn2/1pp5/3P4/2NBPN2/PP2QPPP/R1B2RK1 b kq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rn1qkb1r/1b3ppp/p3pn2/1pp5/3P4/2NBPN2/PP2QPPP/R1B2RK1 b kq -', 'toKey' => 'r2qkb1r/1b1n1ppp/p3pn2/1pp5/3P4/2NBPN2/PP2QPPP/R1B2RK1 w kq -'],
    ];

    public const BOTVINNIK_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'c1g5', 'san' => 'Bg5', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R b KQkq -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p2B1/2PP4/2N2N2/PP2PPPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/6B1/2pP4/2N2N2/PP2PPPP/R2QKB1R w KQkq -'],
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/6B1/2pP4/2N2N2/PP2PPPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/6B1/2pPP3/2N2N2/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'b7b5', 'san' => 'b5', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/6B1/2pPP3/2N2N2/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p4ppp/2p1pn2/1p4B1/2pPP3/2N2N2/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'rnbqkb1r/p4ppp/2p1pn2/1p4B1/2pPP3/2N2N2/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p4ppp/2p1pn2/1p2P1B1/2pP4/2N2N2/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'h7h6', 'san' => 'h6', 'fromKey' => 'rnbqkb1r/p4ppp/2p1pn2/1p2P1B1/2pP4/2N2N2/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p4pp1/2p1pn1p/1p2P1B1/2pP4/2N2N2/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'g5h4', 'san' => 'Bh4', 'fromKey' => 'rnbqkb1r/p4pp1/2p1pn1p/1p2P1B1/2pP4/2N2N2/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p4pp1/2p1pn1p/1p2P3/2pP3B/2N2N2/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'g7g5', 'san' => 'g5', 'fromKey' => 'rnbqkb1r/p4pp1/2p1pn1p/1p2P3/2pP3B/2N2N2/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p4p2/2p1pn1p/1p2P1p1/2pP3B/2N2N2/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'f3g5', 'san' => 'Nxg5', 'fromKey' => 'rnbqkb1r/p4p2/2p1pn1p/1p2P1p1/2pP3B/2N2N2/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p4p2/2p1pn1p/1p2P1N1/2pP3B/2N5/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'h6g5', 'san' => 'hxg5', 'fromKey' => 'rnbqkb1r/p4p2/2p1pn1p/1p2P1N1/2pP3B/2N5/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p4p2/2p1pn2/1p2P1p1/2pP3B/2N5/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'h4g5', 'san' => 'Bxg5', 'fromKey' => 'rnbqkb1r/p4p2/2p1pn2/1p2P1p1/2pP3B/2N5/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p4p2/2p1pn2/1p2P1B1/2pP4/2N5/PP3PPP/R2QKB1R b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqkb1r/p4p2/2p1pn2/1p2P1B1/2pP4/2N5/PP3PPP/R2QKB1R b KQkq -', 'toKey' => 'r1bqkb1r/p2n1p2/2p1pn2/1p2P1B1/2pP4/2N5/PP3PPP/R2QKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'r1bqkb1r/p2n1p2/2p1pn2/1p2P1B1/2pP4/2N5/PP3PPP/R2QKB1R w KQkq -', 'toKey' => 'r1bqkb1r/p2n1p2/2p1pn2/1p2P1B1/2pP4/2N3P1/PP3P1P/R2QKB1R b KQkq -'],
    ];

    public const ANTI_MERAN_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pp2pppp/2p5/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pp2pppp/2p2n2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N2N2/PP2PPPP/R1BQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbqkb1r/pp3ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PP3PPP/R1BQKB1R w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PPQ2PPP/R1B1KB1R b KQkq -'],
        ['uci' => 'f8d6', 'san' => 'Bd6', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2p1pn2/3p4/2PP4/2N1PN2/PPQ2PPP/R1B1KB1R b KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/3p4/2PP4/2N1PN2/PPQ2PPP/R1B1KB1R w KQkq -'],
        ['uci' => 'e3e4', 'san' => 'e4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/3p4/2PP4/2N1PN2/PPQ2PPP/R1B1KB1R w KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/3p4/2PPP3/2N2N2/PPQ2PPP/R1B1KB1R b KQkq -'],
        ['uci' => 'd5e4', 'san' => 'dxe4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/3p4/2PPP3/2N2N2/PPQ2PPP/R1B1KB1R b KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/8/2PPp3/2N2N2/PPQ2PPP/R1B1KB1R w KQkq -'],
        ['uci' => 'c3e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/8/2PPp3/2N2N2/PPQ2PPP/R1B1KB1R w KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/8/2PPN3/5N2/PPQ2PPP/R1B1KB1R b KQkq -'],
        ['uci' => 'f6e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbpn2/8/2PPN3/5N2/PPQ2PPP/R1B1KB1R b KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbp3/8/2PPn3/5N2/PPQ2PPP/R1B1KB1R w KQkq -'],
        ['uci' => 'c2e4', 'san' => 'Qxe4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbp3/8/2PPn3/5N2/PPQ2PPP/R1B1KB1R w KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pbp3/8/2PPQ3/5N2/PP3PPP/R1B1KB1R b KQkq -'],
        ['uci' => 'e6e5', 'san' => 'e5', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pbp3/8/2PPQ3/5N2/PP3PPP/R1B1KB1R b KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pb4/4p3/2PPQ3/5N2/PP3PPP/R1B1KB1R w KQkq -'],
        ['uci' => 'd4e5', 'san' => 'dxe5', 'fromKey' => 'r1bqk2r/pp1n1ppp/2pb4/4p3/2PPQ3/5N2/PP3PPP/R1B1KB1R w KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2pb4/4P3/2P1Q3/5N2/PP3PPP/R1B1KB1R b KQkq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish the Semi-Slav Defense course: Wade, Chigorin, early ...a6 Meran, Botvinnik, Anti-Meran';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. d4 and 2. c4. In the Semi-Slav you build a pawn triangle on c6, d5 and e6: your centre is very solid, but the bishop on c8 is shut in, so your plans aim to free it. In the first three lines White plays e3 and Bd3, and you take on c4 and expand with ...b5: the Wade Variation with an early ...Bb7 and ...b4, a calmer setup with ...Bd6 and quick castling, and a move order with ...a6 first. The fourth line is the sharp Botvinnik System against 5. Bg5, where you hold the c4 pawn and White sacrifices a knight on g5. The fifth meets the Anti-Meran 6. Qc2 with ...Bd6 and the freeing ...e5 break. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['meran-wade-larsen', 'Meran, Wade Variation: ...Bb7, ...b4 and a knight on d5', 'Eleven Black moves. Take on c4, gain time with ...b5 and develop the bishop to b7 at once, then answer e4 with ...b4 and ...c5 and plant a knight on d5. Source: Meran Variation, Wade Variation, Larsen Variation.', self::WADE_SOURCE, self::WADE_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', "Take central space and stop White's d-pawn from advancing further. Your pawn on d5 also controls c4 and e4."],
                ['c4', 'Move your pawn from c2 to c4.', "White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen's Gambit."],
                ['c6', 'Move your pawn from c7 to c6.', 'Support d5 with a pawn, so your centre holds even if White captures there. In the Semi-Slav this pawn will also support a later ...b5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops a knight and adds a second attacker to d5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It defends d5 and controls e4.'],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 and opens the diagonal of the bishop on f1, which now defends c4. The bishop on c1 stays behind the e3 pawn for now.'],
                ['e6', 'Move your pawn from e7 to e6.', 'This is the Semi-Slav: your pawns on c6, d5 and e6 form a triangle around d5, and the bishop on f8 can now come out. The cost is that e6 shuts in your bishop on c8, so your next plans aim to give it a diagonal.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the second knight, defends d4 and controls e5.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight to d7, its natural square now that your pawn stands on c6. From d7 the knight covers the c5 and e5 squares, supporting your later ...c5 or ...e5 break.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop towards your kingside, where it aims at h7, and prepares the advance e3-e4.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white pawn.', "Take on c4 now that White's bishop has moved to d3: recapturing costs that bishop a second move. This capture starts the Meran plan of gaining queenside space with your pawns."],
                ['Bxc4', 'Move your bishop from d3 to c4, capturing the black pawn.', 'White wins the pawn back with the bishop. Material is level.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Attack the bishop with a pawn that your c6 pawn supports. The b-pawn gains queenside space and clears b7 for your light-squared bishop.'],
                ['Bd3', 'Move your bishop from c4 to d3.', 'White retreats to d3, where the bishop points at h7 again and supports e4. It also attacks your pawn on b5, which your c6 pawn defends.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'This is the Wade Variation: develop the bishop to b7 at once, before ...a6. Your c6 pawn blocks it for now, but once your c-pawn advances it will look through the centre towards e4 and the white kingside.'],
                ['e4', 'Move your pawn from e3 to e4.', 'White builds a pawn centre on d4 and e4, supported by the knight on c3 and the bishop on d3. Watch for the advance e4-e5, which would drive your knight away from f6.'],
                ['b4', 'Move your pawn from b5 to b4.', 'Attack the knight on c3, one of the two pieces defending e4. Once it moves away, e4 has only the bishop on d3 to protect it.'],
                ['Na4', 'Move your knight from c3 to a4.', 'White puts the knight on a4, where it controls c5, the square your c-pawn wants to reach. Now e4 is defended only by the bishop on d3.'],
                ['c5', 'Move your pawn from c6 to c5.', 'Play ...c5 anyway. The pawn attacks d4 and protects your b4 pawn, and the bishop on f8 and knight on d7 support it. With the c6 pawn gone, your bishop on b7 now hits e4 as well.'],
                ['e5', 'Move your pawn from e4 to e5.', 'White advances the e-pawn before it can be won, attacking your knight on f6. The pawn on e5 is supported by d4 and the knight on f3, and with e4 cleared your bishop on b7 now looks at the knight on f3.'],
                ['Nd5', 'Move your knight from f6 to d5.', "Put your knight on d5, a strong central square: White's c-pawn is gone and the e-pawn has passed e4, so for now no white pawn can attack the knight there. The knight also protects b4. The named Larsen Variation ends here: your next tasks are to resolve the tension on d4, develop the bishop on f8 and castle, and watch h7, which White's bishop attacks and only your rook defends."],
            ]],
            ['meran-chigorin-bd6', 'Meran with ...Bd6: both bishops out and quick castling', 'Ten Black moves. Reach the Meran with ...dxc4 and ...b5, then develop the bishops to d6 and b7 and castle, while White guards b4 with a3. Source: Chigorin Defense.', self::CHIGORIN_SOURCE, self::CHIGORIN_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', "Take central space and stop White's d-pawn from advancing further. Your pawn on d5 also controls c4 and e4."],
                ['c4', 'Move your pawn from c2 to c4.', "White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen's Gambit."],
                ['c6', 'Move your pawn from c7 to c6.', 'Support d5 with a pawn, so your centre holds even if White captures there. In the Semi-Slav this pawn will also support a later ...b5.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops a knight and adds a second attacker to d5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It defends d5 and controls e4.'],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 and opens the diagonal of the bishop on f1, which now defends c4. The bishop on c1 stays behind the e3 pawn for now.'],
                ['e6', 'Move your pawn from e7 to e6.', 'This is the Semi-Slav: your pawns on c6, d5 and e6 form a triangle around d5, and the bishop on f8 can now come out. The cost is that e6 shuts in your bishop on c8, so your next plans aim to give it a diagonal.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the second knight, defends d4 and controls e5.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight to d7, its natural square now that your pawn stands on c6. From d7 the knight covers the c5 and e5 squares, supporting your later ...c5 or ...e5 break.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop towards your kingside, where it aims at h7, and prepares the advance e3-e4.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white pawn.', "Take on c4 now that White's bishop has moved to d3: recapturing costs that bishop a second move. This capture starts the Meran plan of gaining queenside space with your pawns."],
                ['Bxc4', 'Move your bishop from d3 to c4, capturing the black pawn.', 'White wins the pawn back with the bishop. Material is level.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Attack the bishop with a pawn that your c6 pawn supports. The b-pawn gains queenside space and clears b7 for your light-squared bishop.'],
                ['Bd3', 'Move your bishop from c4 to d3.', 'White retreats to d3, where the bishop points at h7 again and supports e4. It also attacks your pawn on b5, which your c6 pawn defends.'],
                ['Bd6', 'Move your bishop from f8 to d6.', 'This is the Chigorin Defense. Develop the kingside bishop first and get ready to castle. On d6 it aims at h2 and covers e5, the square for a later ...e5 break.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles. The h2 pawn that your bishop eyes is defended by the knight on f3 and the king.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Develop the light-squared bishop on b7, the square your ...b5 cleared. Your c6 pawn blocks it for now, but it will look down the long diagonal once that pawn advances or the centre opens.'],
                ['a3', 'Move your pawn from a2 to a3.', 'White makes ...b4 harder: the pawn on a3 covers b4, so if you push ...b4 to chase the knight on c3 away from the centre, White can take with the a-pawn and your bishop on d6 has to recapture.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', "Put your king in safety. Your king and your knight on f6 now guard h7, the pawn White's bishop on d3 attacks."],
                ['Qc2', 'Move your queen from d1 to c2.', 'White puts the queen behind the bishop on d3, so both now aim at h7. The named Chigorin Defense ends here: your next tasks are to prepare a central break, ...e5 or ...c5, which your bishop on d6 and knight on d7 both support, and to keep the knight on f6 ready to defend h7.'],
            ]],
            ['meran-early-a6', 'Meran with ...a6 first: ...b5 and an early ...c5', 'Ten Black moves. Reach the Semi-Slav through the Queen\'s Gambit Declined move order, play ...a6 before ...dxc4 and ...b5, then strike with ...c5 before your knight goes to d7. Source: Meran Variation.', self::A6_MERAN_SOURCE, self::A6_MERAN_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', "Take central space and stop White's d-pawn from advancing further."],
                ['c4', 'Move your pawn from c2 to c4.', 'White offers the c-pawn to pull your d-pawn away from the centre.'],
                ['e6', 'Move your pawn from e7 to e6.', "Support d5 with the e-pawn, as in the Queen's Gambit Declined. The pawn also opens a path for the bishop on f8."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops a knight and adds a second attacker to d5.'],
                ['c6', 'Move your pawn from c7 to c6.', "Support d5 with the c-pawn as well. With pawns on c6, d5 and e6 you have the Semi-Slav triangle, reached here through the Queen's Gambit Declined move order."],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 and opens the diagonal of the bishop on f1, which now defends c4.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It adds another defender to d5 and controls e4.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the second knight, defends d4 and controls e5.'],
                ['a6', 'Move your pawn from a7 to a6.', 'Prepare ...b5 before you even take on c4. With ...a6 played, a pawn on b5 will be supported twice, and your c-pawn can later advance to c5 without leaving b5 unprotected.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop towards your kingside, where it aims at h7, and keeps the c4 pawn defended.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white pawn.', 'Take on c4 now that the bishop has moved to d3: recapturing costs it a second move.'],
                ['Bxc4', 'Move your bishop from d3 to c4, capturing the black pawn.', 'White wins the pawn back. Material is level, and the bishop on c4 eyes your pawns on a6 and e6.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Gain time by attacking the bishop. Your b5 pawn is supported by both the a6 and the c6 pawn, so White cannot simply take it.'],
                ['Bd3', 'Move your bishop from c4 to d3.', 'White retreats to d3, where the bishop again aims at h7 and attacks your b5 pawn together with the knight on c3.'],
                ['c5', 'Move your pawn from c6 to c5.', 'Strike at d4 at once. Because you played ...a6 early, your b5 pawn stays protected after the c-pawn leaves c6, and you did not need ...Nbd7 first. The bishop on f8 supports c5.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and keeps the central tension. Your c5 pawn and queen attack d4, which the e3 pawn and the knight on f3 defend.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Develop the bishop to b7. With the c6 pawn gone, it already looks down the long diagonal at the knight on f3 and keeps an eye on e4, the square White wants for the e-pawn.'],
                ['Qe2', 'Move your queen from d1 to e2.', 'White brings the queen to e2. It defends the knight on f3 a second time, will support the pawn if it advances to e4, and leaves d1 free for a rook.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop your last minor piece on the queenside. On d7 the knight adds a second defender to c5, after the bishop on f8. The named Meran Variation ends here: your next tasks are to develop the bishop on f8 and castle, while choosing the right moment to resolve the tension between your c5 pawn and White\'s d4 pawn.'],
            ]],
            ['botvinnik-lilienthal', 'Botvinnik System: ...dxc4, ...b5 and ...g5 against 5. Bg5', 'Ten Black moves. Meet 5. Bg5 by taking on c4 and holding the pawn with ...b5, break the pin with ...h6 and ...g5, and take the knight that White sacrifices on g5. Source: Botvinnik Variation, Lilienthal Variation.', self::BOTVINNIK_SOURCE, self::BOTVINNIK_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', "Take central space and stop White's d-pawn from advancing further. Your pawn on d5 also controls c4 and e4."],
                ['c4', 'Move your pawn from c2 to c4.', "White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen's Gambit."],
                ['c6', 'Move your pawn from c7 to c6.', 'Support d5 with a pawn, so your centre holds even if White captures there. In the Semi-Slav this pawn will also support a later ...b5.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops a knight, defends d4 and controls e5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It defends d5 and controls e4.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the second knight and adds another attacker to d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Complete the Semi-Slav triangle with pawns on c6, d5 and e6. Your centre is now very solid and the bishop on f8 can come out, while your bishop on c8 is shut in for the moment.'],
                ['Bg5', 'Move your bishop from c1 to g5.', 'White develops the bishop and pins your knight on f6 against your queen: if the knight moved, the bishop could take the queen on d8. White keeps the e-pawn at home for now, ready to advance it two squares.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white pawn.', 'This is the Botvinnik System. Take the c4 pawn and plan to hold it with ...b5, while White builds a big centre and attacks. With your d-pawn gone, your queen also looks down the d-file at d4.'],
                ['e4', 'Move your pawn from e2 to e4.', 'White takes the whole centre, and the bishop on f1 now attacks your pawn on c4. The pawn on e4 is attacked by your knight but defended by the knight on c3.'],
                ['b5', 'Move your pawn from b7 to b5.', 'Defend the c4 pawn with your b-pawn, which the c6 pawn in turn supports. Holding on to the extra pawn is the point of your ...dxc4.'],
                ['e5', 'Move your pawn from e4 to e5.', 'White advances with a threat: the pawn on e5 attacks your knight on f6, which is still pinned against your queen.'],
                ['h6', 'Move your pawn from h7 to h6.', 'Attack the bishop that pins your knight, so White has to decide at once whether to capture on f6 or retreat the bishop.'],
                ['Bh4', 'Move your bishop from g5 to h4.', 'White keeps the pin by retreating along the same diagonal. Your knight on f6 is still attacked by the e5 pawn and the bishop.'],
                ['g5', 'Move your pawn from g7 to g5.', 'Break the pin: your pawn on g5 now stands between the bishop and your knight, and it attacks the bishop on h4. The knight on f6 is attacked only by the e5 pawn, and your queen defends it.'],
                ['Nxg5', 'Move your knight from f3 to g5, capturing the black pawn.', 'White sacrifices the knight for a pawn. The knight on g5 attacks your pawns on e6 and f7, and if you take it, the bishop recaptures on g5 and pins your knight on f6 again.'],
                ['hxg5', 'Move your pawn from h6 to g5, capturing the white knight.', 'Take the knight. Count the material: you have won a knight and the c4 pawn, White has won one pawn. Your rook on h8 now attacks the bishop on h4 down the opened h-file, as does your pawn on g5.'],
                ['Bxg5', 'Move your bishop from h4 to g5, capturing the black pawn.', "White takes a second pawn and pins your knight against the queen once more. You have an extra knight and White an extra pawn, but White's e5 pawn and bishop both attack the pinned knight, and only your queen defends it."],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight and add a second defender to f6. The knight on d7 also attacks the pawn on e5, which only the d4 pawn defends.'],
                ['g3', 'Move your pawn from g2 to g3.', 'White prepares to put the bishop on g2, on the long diagonal towards your c6 pawn. White can also take your pinned knight with the e5 pawn at any moment, so your extra piece is not permanent. The named Lilienthal Variation ends here: your next tasks are to develop the bishop to b7, bring your queen into play and find a safe place for your king, whose g- and h-pawns are gone.'],
            ]],
            ['anti-meran-qc2', 'Anti-Meran 6. Qc2: ...Bd6 and the ...e5 break', 'Nine Black moves. Against 6. Qc2 develop the bishop to d6, exchange pawns and knights when White plays e4, and free your position with ...e5. Source: Stoltz Variation, Center Variation, Mikhalchishin Line.', self::ANTI_MERAN_SOURCE, self::ANTI_MERAN_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['d5', 'Move your pawn from d7 to d5.', "Take central space and stop White's d-pawn from advancing further. Your pawn on d5 also controls c4 and e4."],
                ['c4', 'Move your pawn from c2 to c4.', "White offers the c-pawn to pull your d-pawn away from the centre. This is the Queen's Gambit."],
                ['c6', 'Move your pawn from c7 to c6.', 'Support d5 with a pawn, so your centre holds even if White captures there. In the Semi-Slav this pawn will also support a later ...b5.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops a knight, defends d4 and controls e5.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It defends d5 and controls e4.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the second knight and adds another attacker to d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Complete the Semi-Slav triangle with pawns on c6, d5 and e6. Your centre is now very solid and the bishop on f8 can come out, while your bishop on c8 is shut in for the moment.'],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 and opens the diagonal of the bishop on f1, which now defends c4.'],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight to d7, its natural square now that your pawn stands on c6. From d7 the knight covers the c5 and e5 squares, supporting your later ...c5 or ...e5 break.'],
                ['Qc2', 'Move your queen from d1 to c2.', 'This is the Stoltz Variation, often called the Anti-Meran. Instead of Bd3, which would let you take on c4 and make the bishop move twice, White puts the queen on c2. From there it controls e4, supporting the advance e3-e4, and aims at h7.'],
                ['Bd6', 'Move your bishop from f8 to d6.', 'Develop the bishop to d6, where it eyes h2 and supports the ...e5 break. You are also ready to castle.'],
                ['e4', 'Move your pawn from e3 to e4.', 'White strikes in the centre at once. The pawn on e4 attacks d5 and is defended by the knight on c3 and the queen. If nothing changes, White may push on to e5 and drive your knight from f6.'],
                ['dxe4', 'Move your pawn from d5 to e4, capturing the white pawn.', 'Release the tension by taking on e4. Your pawn now attacks the knight on f3, but it is attacked twice and defended once, so White will win it back straight away.'],
                ['Nxe4', 'Move your knight from c3 to e4, capturing the black pawn.', 'White recaptures with the knight, which attacks your bishop on d6 and your knight on f6. Your bishop is undefended, because your own knight on d7 blocks the queen.'],
                ['Nxe4', 'Move your knight from f6 to e4, capturing the white knight.', 'Take the knight that attacked your bishop. Your knight on e4 now defends the bishop on d6 and attacks f2, and White will recapture.'],
                ['Qxe4', 'Move your queen from c2 to e4, capturing the black knight.', 'White recaptures with the queen, which stands in the centre. It attacks your pawns on c6, e6 and h7, and pins the e6 pawn to your king on the e-file. Material is level.'],
                ['e5', 'Move your pawn from e6 to e5.', 'Strike back with the freeing ...e5. The pinned pawn can still move along the e-file. It attacks d4 and is defended by your knight on d7 and bishop on d6. Once the knight on d7 moves, your bishop on c8 will have an open diagonal.'],
                ['dxe5', 'Move your pawn from d4 to e5, capturing the black pawn.', 'White takes on e5, so for the moment White is a pawn up, and the pawn attacks your undefended bishop on d6. The named Mikhalchishin Line ends here: your next task is to win the pawn back on e5, which your knight and bishop both attack. Remember that any piece you put on e5 stands between White\'s queen and your king on the e-file.'],
            ]],
        ];

        if (false !== $this->connection->fetchOne("SELECT id FROM lesson WHERE id='semi-slav'")) {
            throw new \RuntimeException('The Semi-Slav Defense course already exists.');
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Semi-Slav Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }
        $start = $this->connection->fetchOne('SELECT id FROM chess_position WHERE position_key=?', [self::WADE_FACTS[0]['fromKey']]);
        if (false === $start) {
            throw new \RuntimeException('The imported Semi-Slav Defense start position is missing.');
        }

        $this->addSql("INSERT INTO lesson (id,current_revision,sort_order) VALUES ('semi-slav',NULL,20)");
        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) VALUES ('semi-slav',1,?,?,?,UTC_TIMESTAMP(),'b')", ['Semi-Slav Defense', $introduction, (int) $start]);
        foreach ($lines as $index => [$lineId, $title, $description, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('semi-slav',1,?,?,?,?,?)", [$lineId, $title, $description, $index, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('semi-slav',1,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Semi-Slav Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=1 WHERE id='semi-slav'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve Semi-Slav Defense sessions and progress.');
    }
}
