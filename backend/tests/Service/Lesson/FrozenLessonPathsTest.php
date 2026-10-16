<?php

declare(strict_types=1);

namespace App\Tests\Service\Lesson;

use App\Service\OpeningImport\PChessMoveReplayer;
use AppMigrations\LessonData\Version20261015010300;
use AppMigrations\LessonData\Version20261015010400;
use AppMigrations\LessonData\Version20261015010500;
use AppMigrations\LessonData\Version20261015010600;
use AppMigrations\LessonData\Version20261015010700;
use AppMigrations\LessonData\Version20261015010800;
use AppMigrations\LessonData\Version20261015010900;
use AppMigrations\LessonData\Version20261015011000;
use AppMigrations\LessonData\Version20261015011100;
use AppMigrations\LessonData\Version20261015011200;
use AppMigrations\LessonData\Version20261015011300;
use AppMigrations\LessonData\Version20261015011400;
use AppMigrations\LessonData\Version20261015011500;
use AppMigrations\LessonData\Version20261015011600;
use AppMigrations\LessonData\Version20261015011700;
use AppMigrations\LessonData\Version20261015011800;
use AppMigrations\LessonData\Version20261016020000;
use AppMigrations\LessonData\Version20261016020100;
use AppMigrations\LessonData\Version20261016020200;
use AppMigrations\LessonData\Version20261016020300;
use AppMigrations\LessonData\Version20261016020400;
use AppMigrations\LessonData\Version20261016020500;
use AppMigrations\LessonData\Version20261016020600;
use AppMigrations\LessonData\Version20261016020700;
use AppMigrations\LessonData\Version20261016020800;
use AppMigrations\LessonData\Version20261016030000;
use AppMigrations\LessonData\Version20261016030100;
use AppMigrations\LessonData\Version20261016030200;
use AppMigrations\LessonData\Version20261016030300;
use AppMigrations\LessonData\Version20261016030400;
use AppMigrations\LessonData\Version20261016030500;
use AppMigrations\LessonData\Version20261016030600;
use AppMigrations\LessonData\Version20261016030700;
use AppMigrations\LessonData\Version20261016030800;
use AppMigrations\LessonData\Version20261016030900;
use AppMigrations\LessonData\Version20261016031100;
use AppMigrations\LessonData\Version20261016031200;
use AppMigrations\LessonData\Version20261016031300;
use AppMigrations\LessonData\Version20261016031400;
use AppMigrations\LessonData\Version20261016031500;
use AppMigrations\LessonData\Version20261016031600;
use AppMigrations\LessonData\Version20261016031700;
use AppMigrations\LessonData\Version20261016031800;
use AppMigrations\LessonData\Version20261016031900;
use AppMigrations\LessonData\Version20261016032000;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FrozenLessonPathsTest extends TestCase
{
    /** The published teaching contract; a correction must introduce another revision. */
    private const EXPECTED_PATHS = [
        'italian/1/centre-plan' => [8, 'e4 e5 Nf3 Nc6 Bc4 Bc5 c3 Nf6'],
        'italian/1/quiet-plan' => [8, 'e4 e5 Nf3 Nc6 Bc4 Bc5 d3 Nf6'],
        'london/1/first-setup' => [8, 'd4 Nf6 Nf3 g6 Bf4 Bg7 e3 d6'],
        'london/2/first-setup' => [6, 'd4 d5 Nf3 e6 Bf4 Nf6'],
        'london/3/first-setup' => [15, 'd4 d5 Nf3 Nf6 Bf4 e6 e3 c5 c3 Nc6 Nbd2 Be7 Bd3 O-O O-O'],
        'caro-kann/1/exchange-setup' => [10, 'e4 c6 d4 d5 exd5 cxd5 Bd3 Nc6 c3 Nf6'],
        'caro-kann/1/panov-development' => [18, 'e4 c6 d4 d5 exd5 cxd5 c4 Nc6 Nc3 Nf6 Nf3 e6 cxd5 exd5 Bb5 Bd6 Bg5 O-O'],
        'french/1/advance-centre' => [14, 'e4 e6 d4 d5 e5 c5 c3 Nc6 Nf3 Qb6 Bd3 cxd4 cxd4 Bd7'],
        'queens-gambit/1/declined-development' => [14, 'd4 d5 c4 e6 Nc3 Nf6 Nf3 c5 e3 Nc6 Bd3 Bd6 O-O O-O'],
        'italian/2/pianissimo-main-line' => [17, 'e4 e5 Nf3 Nc6 Bc4 Bc5 c3 Nf6 d3 d6 O-O O-O Re1 a6 Bb3 Ba7 h3'],
        'italian/2/two-knights-knorre' => [23, 'e4 e5 Nf3 Nc6 Bc4 Nf6 Ng5 d5 exd5 Na5 Bb5+ c6 dxc6 bxc6 Be2 h6 Nf3 e4 Ne5 Bd6 d4 Qc7 Bd2'],
        'caro-kann/2/classical-main-line' => [24, 'e4 c6 d4 d5 Nd2 dxe4 Nxe4 Bf5 Ng3 Bg6 h4 h6 Nf3 Nd7 h5 Bh7 Bd3 Bxd3 Qxd3 e6 Bd2 Ngf6 O-O-O Be7'],
        'caro-kann/2/karpov-main-line' => [19, 'e4 c6 d4 d5 Nd2 dxe4 Nxe4 Nd7 Ng5 Ngf6 Bd3 e6 N1f3 Bd6 Qe2 h6 Ne4 Nxe4 Qxe4'],
        'french/2/classical-stahlberg' => [20, 'e4 e6 d4 d5 Nc3 Nf6 Bg5 Be7 e5 Nfd7 Bxe7 Qxe7 f4 O-O Nf3 c5 Qd2 Nc6 O-O-O c4'],
        'french/2/winawer-positional' => [18, 'e4 e6 d4 d5 Nc3 Bb4 e5 c5 a3 Bxc3+ bxc3 Ne7 a4 Nbc6 Nf3 Qa5 Bd2 Bd7'],
        'queens-gambit/2/declined-development' => [19, 'd4 d5 c4 e6 Nc3 Nf6 Nf3 c5 e3 Nc6 Bd3 Bd6 O-O O-O Qe2 Qe7 dxc5 Bxc5 e4'],
        'queens-gambit/2/orthodox-classical' => [28, 'd4 d5 c4 e6 Nc3 Nf6 Bg5 Be7 e3 O-O Nf3 Nbd7 Rc1 c6 Bd3 dxc4 Bxc4 Nd5 Bxe7 Qxe7 O-O Nxc3 Rxc3 e5 dxe5 Nxe5 Nxe5 Qxe5'],
        'queens-gambit/2/accepted-classical' => [26, 'd4 d5 c4 dxc4 Nf3 Nf6 e3 e6 Bxc4 c5 O-O a6 Qe2 b5 Bb3 Nc6 Rd1 c4 Bc2 Nb4 Nc3 Nxc2 Qxc2 Bb7 d5 Qc7'],
        'ruy-lopez/1/chigorin-main-line' => [28, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Be7 Re1 b5 Bb3 d6 c3 O-O h3 Na5 Bc2 c5 d4 Qc7 Nbd2 Bd7 Nf1 Rfe8 Ne3 g6'],
        'ruy-lopez/1/breyer-defense' => [24, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Be7 Re1 b5 Bb3 d6 c3 O-O h3 Nb8 d4 Nbd7 Nbd2 Bb7 Bc2 c5'],
        'ruy-lopez/1/marshall-attack' => [36, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Be7 Re1 b5 Bb3 O-O c3 d5 exd5 Nxd5 Nxe5 Nxe5 Rxe5 c6 d4 Bd6 Re1 Qh4 g3 Qh3 Be3 Bg4 Qd3 Rae8 Nd2 Re6 a4 Qh5'],
        'catalan/1/closed-main-line' => [23, 'd4 Nf6 c4 e6 Nf3 d5 g3 Be7 Bg2 O-O O-O Nbd7 Qc2 c6 Bf4 b6 Nbd2 Bb7 e4 dxe4 Nxe4 Nxe4 Qxe4'],
        'catalan/1/closed-sokolsky' => [20, 'd4 Nf6 c4 e6 g3 d5 Bg2 Be7 Nf3 O-O O-O Nbd7 Qc2 c6 Nbd2 b6 b3 a5 Bb2 Ba6'],
        'sicilian/1/najdorf-main-line' => [18, 'e4 c5 Nf3 d6 d4 cxd4 Nxd4 Nf6 Nc3 a6 Bg5 e6 f4 Be7 Qf3 Qc7 O-O-O Nbd7'],
        'sicilian/1/dragon-yugoslav' => [24, 'e4 c5 Nf3 d6 d4 cxd4 Nxd4 Nf6 Nc3 g6 Be3 Bg7 f3 O-O Qd2 Nc6 Bc4 Bd7 O-O-O Qa5 h4 Rfc8 Bb3 h5'],
        'sicilian/1/sveshnikov' => [20, 'e4 c5 Nf3 Nc6 d4 cxd4 Nxd4 Nf6 Nc3 e5 Ndb5 d6 Bg5 a6 Na3 b5 Bxf6 gxf6 Nd5 f5'],
        'kings-indian/1/classical-main-line' => [27, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 Nf3 O-O Be2 e5 O-O Nc6 d5 Ne7 Ne1 Nd7 Be3 f5 f3 f4 Bf2 g5 Rc1 Ng6 c5'],
        'kings-indian/1/fianchetto-panno' => [26, 'd4 Nf6 c4 g6 Nf3 Bg7 g3 O-O Bg2 d6 O-O Nc6 Nc3 a6 d5 Na5 Nd2 c5 Qc2 Rb8 b3 b5 Bb2 bxc4 bxc4 Bh6'],
        'slav/1/czech-main-line' => [17, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 dxc4 a4 Bf5 e3 e6 Bxc4 Bb4 O-O O-O Qe2'],
        'slav/1/semi-slav-meran' => [29, 'd4 d5 c4 c6 Nc3 Nf6 Nf3 e6 e3 Nbd7 Bd3 dxc4 Bxc4 b5 Bd3 a6 e4 c5 e5 cxd4 Nxb5 Nxe5 Nxe5 axb5 O-O Qd5 Qe2 Ba6 Bg5'],
        'italian/3/giuoco-piano-moller' => [27, 'e4 e5 Nf3 Nc6 Bc4 Bc5 c3 Nf6 d4 exd4 cxd4 Bb4+ Nc3 Nxe4 O-O Bxc3 d5 Bf6 Re1 Ne7 Rxe4 d6 Bg5 Bxg5 Nxg5 O-O Nxh7'],
        'caro-kann/3/panov-development' => [19, 'e4 c6 d4 d5 exd5 cxd5 c4 Nc6 Nc3 Nf6 Nf3 e6 cxd5 exd5 Bb5 Bd6 Bg5 O-O O-O'],
        'french/3/tarrasch-chistyakov' => [20, 'e4 e6 d4 d5 Nd2 c5 exd5 Qxd5 Ngf3 cxd4 Bc4 Qd6 O-O Nf6 Nb3 Nc6 Nbxd4 Nxd4 Nxd4 a6'],
        'slav/2/czech-main-line' => [19, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 dxc4 a4 Bf5 e3 e6 Bxc4 Bb4 O-O O-O Qe2 Ne4 g4'],
        'scotch/1/classical-gottschall' => [24, 'e4 e5 Nf3 Nc6 d4 exd4 Nxd4 Bc5 Be3 Qf6 c3 Nge7 Qd2 d5 Nb5 Bxe3 Qxe3 O-O Nxc7 Rb8 Nxd5 Nxd5 exd5 Nb4'],
        'scotch/1/scotch-gambit-max-lange' => [21, 'e4 e5 Nf3 Nc6 d4 exd4 Bc4 Nf6 O-O Bc5 e5 d5 exf6 dxc4 Re1+ Be6 Ng5 Qd5 Nc3 Qf5 Nce4'],
        'four-knights/1/scotch-four-knights' => [17, 'e4 e5 Nf3 Nc6 Nc3 Nf6 d4 exd4 Nxd4 Bb4 Nxc6 bxc6 Bd3 d5 exd5 O-O O-O'],
        'four-knights/1/spanish-symmetrical' => [20, 'e4 e5 Nf3 Nc6 Nc3 Nf6 Bb5 Bb4 O-O O-O d3 d6 Bg5 Bxc3 bxc3 Qe7 Re1 Nd8 d4 Bg4'],
        'four-knights/1/rubinstein-maroczy' => [18, 'e4 e5 Nf3 Nc6 Nc3 Nf6 Bb5 Nd4 Be2 Nxf3+ Bxf3 Bc5 O-O O-O d3 d6 Na4 Bb6'],
        'evans-gambit/1/accepted-steinitz' => [21, 'e4 e5 Nf3 Nc6 Bc4 Bc5 b4 Bxb4 c3 Bc5 d4 exd4 O-O d6 cxd4 Bb6 Nc3 Na5 Bg5 f6 Be3'],
        'evans-gambit/1/accepted-levenfish' => [21, 'e4 e5 Nf3 Nc6 Bc4 Bc5 b4 Bxb4 c3 Ba5 d4 d6 Qb3 Qd7 dxe5 dxe5 O-O Bb6 Ba3 Na5 Nxe5'],
        'petrov/1/classical-maroczy' => [20, 'e4 e5 Nf3 Nf6 Nxe5 d6 Nf3 Nxe4 d4 d5 Bd3 Be7 O-O Nc6 Re1 Bg4 c3 f5 c4 Bh4'],
        'petrov/1/classical-marshall' => [18, 'e4 e5 Nf3 Nf6 Nxe5 d6 Nf3 Nxe4 d4 d5 Bd3 Bd6 O-O O-O c4 c6 Re1 Bg4'],
        'alekhine/1/four-pawns-tartakower' => [22, 'e4 Nf6 e5 Nd5 d4 d6 c4 Nb6 f4 dxe5 fxe5 Nc6 Be3 Bf5 Nc3 e6 Nf3 Qd7 Be2 O-O-O O-O Be7'],
        'alekhine/1/exchange-karpov' => [21, 'e4 Nf6 e5 Nd5 d4 d6 c4 Nb6 exd6 cxd6 Nc3 g6 h3 Bg7 Nf3 O-O Be2 Nc6 O-O Bf5 Bf4'],
        'nimzo-indian/1/rubinstein-bernstein' => [21, 'd4 Nf6 c4 e6 Nc3 Bb4 e3 O-O Bd3 d5 a3 Bxc3+ bxc3 dxc4 Bxc4 c5 Nf3 Nc6 O-O Qc7 Qc2'],
        'nimzo-indian/1/samisch-capablanca' => [18, 'd4 Nf6 c4 e6 Nc3 Bb4 e3 c5 Bd3 Nc6 a3 Bxc3+ bxc3 O-O Ne2 b6 e4 Ne8'],
        'grunfeld/1/exchange-spassky' => [26, 'd4 Nf6 c4 g6 Nc3 d5 cxd5 Nxd5 e4 Nxc3 bxc3 Bg7 Bc4 c5 Ne2 Nc6 Be3 O-O O-O Bg4 f3 Na5 Bd3 cxd4 cxd4 Be6'],
        'grunfeld/1/russian-keres' => [21, 'd4 Nf6 c4 g6 Nc3 d5 Nf3 Bg7 Qb3 dxc4 Qxc4 O-O e4 Bg4 Be3 Nfd7 Be2 Nb6 Qd3 Nc6 O-O-O'],
        'benoni/1/classical-tal-line' => [20, 'd4 Nf6 c4 c5 d5 e6 Nc3 exd5 cxd5 d6 e4 g6 Nf3 Bg7 Be2 O-O O-O Re8 Nd2 Nbd7'],
        'benoni/1/four-pawns-main-line' => [20, 'd4 Nf6 c4 c5 d5 e6 Nc3 exd5 cxd5 d6 e4 g6 f4 Bg7 Nf3 O-O Be2 Nbd7 O-O Re8'],
        'tarrasch/1/classical-main-line' => [22, 'd4 d5 c4 e6 Nc3 c5 cxd5 exd5 Nf3 Nc6 g3 Nf6 Bg2 Be7 O-O O-O Bg5 cxd4 Nxd4 h6 Be3 Re8'],
        'tarrasch/1/classical-advance' => [18, 'd4 d5 c4 e6 Nc3 c5 cxd5 exd5 Nf3 Nc6 g3 Nf6 Bg2 Be7 O-O O-O Bg5 c4'],
        'nimzo-indian/2/gligoric-isolated-pawn' => [18, 'd4 Nf6 c4 e6 Nc3 Bb4 e3 O-O Bd3 d5 Nf3 c5 O-O Nc6 a3 dxc4 Bxc4 cxd4'],
        'nimzo-indian/2/st-petersburg-fianchetto' => [18, 'd4 Nf6 c4 e6 Nc3 Bb4 e3 b6 Bd3 Bb7 Nf3 O-O O-O c5 Na4 cxd4 a3 Be7'],
        'queens-indian/1/spassky-isolated-pawn' => [18, 'd4 Nf6 c4 e6 Nf3 b6 e3 Bb7 Bd3 c5 Nc3 cxd4 exd4 Be7 O-O d5 cxd5 Nxd5'],
        'queens-indian/1/bogo-retreat' => [20, 'd4 Nf6 c4 e6 Nf3 d5 g3 Bb4+ Bd2 Be7 Bg2 O-O O-O c6 Qc2 Nbd7 Bf4 b6 Nbd2 Ba6'],
        'grunfeld/2/modern-exchange-pawn-grab' => [20, 'd4 Nf6 c4 g6 Nc3 d5 cxd5 Nxd5 e4 Nxc3 bxc3 Bg7 Nf3 c5 Rb1 O-O Be2 Nc6 d5 Bxc3+'],
        'benoni/2/classical-argentine' => [20, 'd4 Nf6 c4 e6 Nf3 c5 d5 exd5 cxd5 d6 Nc3 g6 e4 Bg7 Be2 O-O O-O a6 a4 Bg4'],
        'benoni/2/fianchetto-hastings' => [22, 'd4 Nf6 c4 e6 g3 c5 d5 exd5 cxd5 d6 Nc3 g6 Bg2 Bg7 Nf3 O-O O-O a6 a4 Nbd7 Nd2 Re8'],
        'semi-slav/1/meran-wade-larsen' => [22, 'd4 d5 c4 c6 Nc3 Nf6 e3 e6 Nf3 Nbd7 Bd3 dxc4 Bxc4 b5 Bd3 Bb7 e4 b4 Na4 c5 e5 Nd5'],
        'semi-slav/1/meran-chigorin-bd6' => [21, 'd4 d5 c4 c6 Nc3 Nf6 e3 e6 Nf3 Nbd7 Bd3 dxc4 Bxc4 b5 Bd3 Bd6 O-O Bb7 a3 O-O Qc2'],
        'semi-slav/1/meran-early-a6' => [20, 'd4 d5 c4 e6 Nc3 c6 e3 Nf6 Nf3 a6 Bd3 dxc4 Bxc4 b5 Bd3 c5 O-O Bb7 Qe2 Nbd7'],
        'semi-slav/1/botvinnik-lilienthal' => [21, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 e6 Bg5 dxc4 e4 b5 e5 h6 Bh4 g5 Nxg5 hxg5 Bxg5 Nbd7 g3'],
        'semi-slav/1/anti-meran-qc2' => [19, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 e6 e3 Nbd7 Qc2 Bd6 e4 dxe4 Nxe4 Nxe4 Qxe4 e5 dxe5'],
        'tarrasch/2/reti-dxc5' => [19, 'd4 d5 c4 e6 Nc3 c5 cxd5 exd5 Nf3 Nc6 g3 Nf6 Bg2 Be7 O-O O-O dxc5 Bxc5 Na4'],
        'tarrasch/2/stoltz-b6' => [20, 'd4 d5 c4 e6 Nf3 c5 cxd5 exd5 g3 Nc6 Bg2 Nf6 O-O Be7 Nc3 O-O Bg5 Be6 Rc1 b6'],
        'slav/3/czech-main-line' => [19, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 dxc4 a4 Bf5 e3 e6 Bxc4 Bb4 O-O O-O Qe2 Ne4 g4'],
        'slav/3/semi-slav-meran' => [29, 'd4 d5 c4 c6 Nc3 Nf6 Nf3 e6 e3 Nbd7 Bd3 dxc4 Bxc4 b5 Bd3 a6 e4 c5 e5 cxd4 Nxb5 Nxe5 Nxe5 axb5 O-O Qd5 Qe2 Ba6 Bg5'],
        'slav/3/czech-morozevich' => [22, 'd4 d5 c4 c6 Nf3 Nf6 Nc3 dxc4 a4 Bf5 Ne5 Nbd7 Nxc4 Qc7 g3 e5 dxe5 Nxe5 Bf4 Nfd7 Bg2 g5'],
        'alekhine/2/four-pawns-korchnoi' => [20, 'e4 Nf6 e5 Nd5 d4 d6 c4 Nb6 f4 dxe5 fxe5 Bf5 Nc3 e6 Nf3 Be7 Be2 O-O O-O f6'],
        'caro-kann/4/classical-seirawan' => [24, 'e4 c6 d4 d5 Nd2 dxe4 Nxe4 Bf5 Ng3 Bg6 h4 h6 Nf3 Nd7 h5 Bh7 Bd3 Bxd3 Qxd3 Ngf6 Bd2 e6 O-O-O Bd6'],
        'french/4/winawer-poisoned-pawn' => [19, 'e4 e6 d4 d5 Nc3 Bb4 e5 c5 a3 Bxc3+ bxc3 Ne7 Qg4 Qc7 Qxg7 Rg8 Qxh7 cxd4 Ne2'],
        'french/4/steinitz-brodsky-jones' => [18, 'e4 e6 d4 d5 Nc3 Nf6 e5 Nfd7 f4 c5 dxc5 Nc6 a3 Bxc5 Qg4 O-O Nf3 f6'],
        'four-knights/2/classical-bardeleben' => [18, 'e4 e5 Nf3 Nc6 Nc3 Nf6 Bb5 Bc5 O-O O-O Nxe5 Nxe5 d4 Bd6 f4 Nc6 e5 Bb4'],
        'evans-gambit/2/paulsen-variation' => [20, 'e4 e5 Nf3 Nc6 Bc4 Bc5 b4 Bxb4 c3 Bc5 d4 exd4 O-O d6 cxd4 Bb6 d5 Na5 Bb2 Ne7'],
        'evans-gambit/2/compromised-defense' => [21, 'e4 e5 Nf3 Nc6 Bc4 Bc5 b4 Bxb4 c3 Ba5 d4 exd4 O-O dxc3 Qb3 Qf6 e5 Qg6 Nxc3 Nge7 Ba3'],
        'two-knights/1/polerio-goring' => [20, 'e4 e5 Nf3 Nc6 Bc4 Nf6 Ng5 d5 exd5 Na5 Bb5+ c6 dxc6 bxc6 Be2 h6 Nf3 e4 Ne5 Qc7'],
        'two-knights/1/polerio-yankovich' => [19, 'e4 e5 Nf3 Nc6 Bc4 Nf6 Ng5 d5 exd5 Na5 d3 h6 Nf3 e4 Qe2 Nxc4 dxc4 Bc5 Nfd2'],
        'two-knights/1/max-lange-berger' => [26, 'e4 e5 Nf3 Nc6 Bc4 Nf6 d4 exd4 O-O Bc5 e5 d5 exf6 dxc4 Re1+ Be6 Ng5 Qd5 Nc3 Qf5 g4 Qg6 Nce4 Bb6 f4 O-O-O'],
        'two-knights/1/max-lange-rubinstein' => [22, 'e4 e5 Nf3 Nc6 Bc4 Nf6 d4 exd4 O-O Bc5 e5 d5 exf6 dxc4 Re1+ Be6 Ng5 Qd5 Nc3 Qf5 Nce4 Bf8'],
        'ruy-lopez/2/zaitsev-hybrid' => [24, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Be7 Re1 b5 Bb3 d6 c3 O-O h3 Bb7 d4 Re8 Nbd2 Bf8 a3 h6'],
        'ruy-lopez/2/open-breslau' => [22, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Nxe4 d4 b5 Bb3 d5 dxe5 Be6 c3 Be7 Re1 O-O Nd4 Nxe5'],
        'ruy-lopez/2/open-howell-ekstrom' => [24, 'e4 e5 Nf3 Nc6 Bb5 a6 Ba4 Nf6 O-O Nxe4 d4 b5 Bb3 d5 dxe5 Be6 Qe2 Be7 Rd1 O-O c4 bxc4 Bxc4 Qd7'],
        'ruy-lopez/2/berlin-wall' => [18, 'e4 e5 Nf3 Nc6 Bb5 Nf6 O-O Nxe4 d4 Nd6 Bxc6 dxc6 dxe5 Nf5 Qxd8+ Kxd8 Nc3 Bd7'],
        'scotch/2/steinitz-rosenthal' => [18, 'e4 e5 Nf3 Nc6 d4 exd4 Nxd4 Qh4 Nb5 Bb4+ Bd2 Qxe4+ Be2 Kd8 O-O Bxd2 Nxd2 Qg6'],
        'scotch/2/scotch-gambit-anderssen' => [21, 'e4 e5 Nf3 Nc6 Bc4 Nf6 d4 exd4 O-O Nxe4 Re1 d5 Bxd5 Qxd5 Nc3 Qa5 Nxe4 Be6 Bd2 Qd5 Bg5'],
        'sicilian/2/richter-rauzer' => [20, 'e4 c5 Nf3 d6 d4 cxd4 Nxd4 Nf6 Nc3 Nc6 Bg5 e6 Qd2 Be7 O-O-O Nxd4 Qxd4 a6 f4 b5'],
        'sicilian/2/sozin-sherbakov' => [20, 'e4 c5 Nf3 d6 d4 cxd4 Nxd4 Nf6 Nc3 Nc6 Bc4 e6 Be3 Be7 Bb3 O-O O-O Na5 f4 b6'],
        'sicilian/2/scheveningen-classical' => [20, 'e4 c5 Nf3 e6 d4 cxd4 Nxd4 Nc6 Nc3 Qc7 Be2 a6 O-O Nf6 Be3 Be7 f4 d6 Qe1 O-O'],
        'sicilian/2/taimanov-modern' => [20, 'e4 c5 Nf3 e6 d4 cxd4 Nxd4 Nc6 Nb5 d6 c4 Nf6 N1c3 a6 Na3 Be7 Be2 O-O O-O b6'],
        'sicilian/2/alapin-endgame' => [18, 'e4 c5 c3 d5 exd5 Qxd5 d4 cxd4 cxd4 Nc6 Nf3 Bg4 Nc3 Bxf3 gxf3 Qxd4 Qxd4 Nxd4'],
        'queens-gambit/3/exchange-reshevsky' => [21, 'd4 Nf6 c4 e6 Nc3 d5 cxd5 exd5 Bg5 c6 e3 Be7 Bd3 Nbd7 Nge2 O-O O-O h6 Bh4 Re8 Qc2'],
        'queens-gambit/3/lasker-bernstein' => [22, 'd4 Nf6 c4 e6 Nc3 d5 Bg5 Be7 e3 h6 Bh4 O-O Nf3 Ne4 Bxe7 Qxe7 cxd5 Nxc3 bxc3 exd5 Qb3 Qd6'],
        'queens-gambit/3/tartakower-defense' => [22, 'd4 d5 c4 e6 Nc3 Be7 Nf3 Nf6 Bg5 h6 Bh4 O-O e3 b6 cxd5 Nxd5 Bxe7 Qxe7 Nxd5 exd5 Rc1 Be6'],
        'queens-gambit/3/cambridge-springs' => [17, 'd4 d5 c4 e6 Nc3 Nf6 Bg5 Nbd7 e3 c6 Nf3 Qa5 Nd2 Bb4 Qc2 O-O Bh4'],
        'catalan/2/closed-zagoryansky' => [17, 'd4 e6 c4 Nf6 Nf3 d5 g3 Be7 Bg2 O-O O-O Nbd7 Qc2 c6 Rd1 b6 a4'],
        'catalan/2/closed-spassky-gambit' => [20, 'd4 Nf6 c4 e6 g3 Be7 Bg2 d5 Nf3 O-O O-O Nbd7 Qc2 c6 b3 b6 Rd1 Bb7 Nc3 b5'],
        'english/1/hedgehog-flexible-formation' => [20, 'c4 e6 Nf3 c5 Nc3 Nf6 g3 b6 Bg2 Bb7 O-O Be7 d4 cxd4 Qxd4 d6 Rd1 a6 b3 Nbd7'],
        'kings-indian/2/classical-main-line' => [27, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 Nf3 O-O Be2 e5 O-O Nc6 d5 Ne7 Ne1 Nd7 Be3 f5 f3 f4 Bf2 g5 Rc1 Ng6 c5'],
        'kings-indian/2/bayonet-sokolov' => [19, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 Nf3 O-O Be2 e5 O-O Nc6 d5 Ne7 b4 Nh5 Re1'],
        'kings-indian/2/petrosian-keres' => [21, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 Nf3 O-O Be2 e5 d5 Nbd7 Bg5 h6 Bh4 g5 Bg3 Nh5 h4'],
        'kings-indian/2/positional-defense' => [18, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 Nf3 O-O Be2 e5 O-O Nbd7 Re1 c6 Bf1 a5'],
        'kings-indian/2/samisch-bronstein' => [24, 'd4 Nf6 c4 g6 Nc3 Bg7 e4 d6 f3 O-O Be3 e5 d5 Nh5 Qd2 Qh4+ g3 Nxg3 Qf2 Nxf1 Qxh4 Nxe3 Ke2 Nxc4'],
    ];

    /** @return iterable<string, array{list<array{uci: string, san: string, fromKey: string, toKey: string}>, int, string}> */
    public static function paths(): iterable
    {
        foreach (self::frozenPaths() as $id => $facts) {
            [$minimumPlies, $expectedSan] = self::EXPECTED_PATHS[$id] ?? throw new \LogicException('Add the new frozen path to its reviewed teaching contract: '.$id);
            yield $id => [$facts, $minimumPlies, $expectedSan];
        }
    }

    public function testEveryPublishedFrozenPathHasARegressionCase(): void
    {
        $expected = array_keys(self::EXPECTED_PATHS);
        $actual = array_keys(self::frozenPaths());
        sort($expected);
        sort($actual);

        self::assertSame($expected, $actual, 'An immutable lesson path was removed or published without regression coverage.');
    }

    /** @param list<array{uci: string, san: string, fromKey: string, toKey: string}> $facts */
    #[DataProvider('paths')]
    public function testFrozenFactsMatchLegalReplay(array $facts, int $minimumPlies, string $expectedSan): void
    {
        self::assertGreaterThanOrEqual($minimumPlies, count($facts), 'The published lesson must retain its useful teaching depth.');
        self::assertLessThanOrEqual(64, count($facts), 'A lesson must remain finite and within the trainer depth limit.');
        self::assertSame(explode(' ', $expectedSan), array_column($facts, 'san'), 'A historical lesson path was changed rather than publishing a new revision.');

        $line = (new PChessMoveReplayer())->replay(array_column($facts, 'san'));
        $previousKey = $line->initialKey;
        self::assertCount(count($facts), $line->moves);
        foreach ($line->moves as $index => $move) {
            $context = 'Frozen ply '.($index + 1).' ('.$move->san.').';
            self::assertSame($previousKey, $facts[$index]['fromKey'], 'Discontinuous source position. '.$context);
            self::assertSame($move->uci, $facts[$index]['uci'], 'Unexpected UCI. '.$context);
            self::assertSame($move->san, $facts[$index]['san'], 'Unexpected SAN. '.$context);
            self::assertSame($move->key, $facts[$index]['toKey'], 'Unexpected canonical destination. '.$context);
            self::assertCount(4, explode(' ', $facts[$index]['fromKey']), 'Source keys exclude the move clocks. '.$context);
            self::assertCount(4, explode(' ', $facts[$index]['toKey']), 'Destination keys exclude the move clocks. '.$context);
            $previousKey = $move->key;
        }
    }

    public function testFullLondonRetainsEightWhiteMovesAndBothCastles(): void
    {
        $facts = self::frozenPaths()['london/3/first-setup'];
        self::assertCount(15, $facts);
        self::assertCount(8, array_filter($facts, static fn (array $fact): bool => 'w' === explode(' ', $fact['fromKey'])[1]));
        self::assertCount(7, array_filter($facts, static fn (array $fact): bool => 'b' === explode(' ', $fact['fromKey'])[1]));
        self::assertSame(['e8g8', 'e1g1'], array_column(array_slice($facts, -2), 'uci'));
        self::assertSame(['O-O', 'O-O'], array_column(array_slice($facts, -2), 'san'));

        $lastPosition = explode(' ', $facts[14]['toKey']);
        $ranks = explode('/', $lastPosition[0]);
        self::assertSame('r1bq1rk1', $ranks[0], 'Black must finish with its king on g8 and castled rook on f8.');
        self::assertSame('R2Q1RK1', $ranks[7], 'White must finish with its king on g1 and castled rook on f1.');
        self::assertSame('-', $lastPosition[2], 'Both kings have castled and lost their castling rights.');
    }

    /** @return array<string, list<array{uci: string, san: string, fromKey: string, toKey: string}>> */
    private static function frozenPaths(): array
    {
        $migrationRoot = dirname(__DIR__, 3).'/migrations';
        require_once $migrationRoot.'/LessonData/Version20261015010300.php';
        require_once $migrationRoot.'/LessonData/Version20261015010400.php';
        require_once $migrationRoot.'/LessonData/Version20261015010500.php';
        require_once $migrationRoot.'/LessonData/Version20261015010600.php';
        require_once $migrationRoot.'/LessonData/Version20261015010700.php';
        require_once $migrationRoot.'/LessonData/Version20261015010800.php';
        require_once $migrationRoot.'/LessonData/Version20261015010900.php';
        require_once $migrationRoot.'/LessonData/Version20261015011000.php';
        require_once $migrationRoot.'/LessonData/Version20261015011100.php';
        require_once $migrationRoot.'/LessonData/Version20261015011200.php';
        require_once $migrationRoot.'/LessonData/Version20261015011300.php';
        require_once $migrationRoot.'/LessonData/Version20261015011400.php';
        require_once $migrationRoot.'/LessonData/Version20261015011500.php';
        require_once $migrationRoot.'/LessonData/Version20261015011600.php';
        require_once $migrationRoot.'/LessonData/Version20261015011700.php';
        require_once $migrationRoot.'/LessonData/Version20261015011800.php';
        require_once $migrationRoot.'/LessonData/Version20261016020000.php';
        require_once $migrationRoot.'/LessonData/Version20261016020100.php';
        require_once $migrationRoot.'/LessonData/Version20261016020200.php';
        require_once $migrationRoot.'/LessonData/Version20261016020300.php';
        require_once $migrationRoot.'/LessonData/Version20261016020400.php';
        require_once $migrationRoot.'/LessonData/Version20261016020500.php';
        require_once $migrationRoot.'/LessonData/Version20261016020600.php';
        require_once $migrationRoot.'/LessonData/Version20261016020700.php';
        require_once $migrationRoot.'/LessonData/Version20261016020800.php';
        require_once $migrationRoot.'/LessonData/Version20261016030000.php';
        require_once $migrationRoot.'/LessonData/Version20261016030100.php';
        require_once $migrationRoot.'/LessonData/Version20261016030200.php';
        require_once $migrationRoot.'/LessonData/Version20261016030300.php';
        require_once $migrationRoot.'/LessonData/Version20261016030400.php';
        require_once $migrationRoot.'/LessonData/Version20261016030500.php';
        require_once $migrationRoot.'/LessonData/Version20261016030600.php';
        require_once $migrationRoot.'/LessonData/Version20261016030700.php';
        require_once $migrationRoot.'/LessonData/Version20261016030800.php';
        require_once $migrationRoot.'/LessonData/Version20261016030900.php';
        require_once $migrationRoot.'/LessonData/Version20261016031100.php';
        require_once $migrationRoot.'/LessonData/Version20261016031200.php';
        require_once $migrationRoot.'/LessonData/Version20261016031300.php';
        require_once $migrationRoot.'/LessonData/Version20261016031400.php';
        require_once $migrationRoot.'/LessonData/Version20261016031500.php';
        require_once $migrationRoot.'/LessonData/Version20261016031600.php';
        require_once $migrationRoot.'/LessonData/Version20261016031700.php';
        require_once $migrationRoot.'/LessonData/Version20261016031800.php';
        require_once $migrationRoot.'/LessonData/Version20261016031900.php';
        require_once $migrationRoot.'/LessonData/Version20261016032000.php';

        /** @var array<string, list<array{uci: string, san: string, fromKey: string, toKey: string}>> $paths */
        $paths = require $migrationRoot.'/Support/Paths20261015.php';
        $constants = [
            'caro-kann/1/exchange-setup' => [Version20261015010300::class, 'EXCHANGE_FACTS'],
            'caro-kann/1/panov-development' => [Version20261015010300::class, 'PANOV_FACTS'],
            'french/1/advance-centre' => [Version20261015010400::class, 'FACTS'],
            'queens-gambit/1/declined-development' => [Version20261015010500::class, 'FACTS'],
            'italian/2/pianissimo-main-line' => [Version20261015010600::class, 'PIANISSIMO_FACTS'],
            'italian/2/two-knights-knorre' => [Version20261015010600::class, 'KNORRE_FACTS'],
            'caro-kann/2/classical-main-line' => [Version20261015010800::class, 'CLASSICAL_FACTS'],
            'caro-kann/2/karpov-main-line' => [Version20261015010800::class, 'KARPOV_FACTS'],
            'french/2/classical-stahlberg' => [Version20261015010900::class, 'STAHLBERG_FACTS'],
            'french/2/winawer-positional' => [Version20261015010900::class, 'WINAWER_FACTS'],
            'queens-gambit/2/declined-development' => [Version20261015010700::class, 'LEVENFISH_FACTS'],
            'queens-gambit/2/orthodox-classical' => [Version20261015010700::class, 'ORTHODOX_FACTS'],
            'queens-gambit/2/accepted-classical' => [Version20261015010700::class, 'ACCEPTED_FACTS'],
            'ruy-lopez/1/chigorin-main-line' => [Version20261015011000::class, 'CHIGORIN_FACTS'],
            'ruy-lopez/1/breyer-defense' => [Version20261015011000::class, 'BREYER_FACTS'],
            'ruy-lopez/1/marshall-attack' => [Version20261015011000::class, 'MARSHALL_FACTS'],
            'catalan/1/closed-main-line' => [Version20261015011100::class, 'CLOSED_FACTS'],
            'catalan/1/closed-sokolsky' => [Version20261015011100::class, 'SOKOLSKY_FACTS'],
            'sicilian/1/najdorf-main-line' => [Version20261015011200::class, 'NAJDORF_FACTS'],
            'sicilian/1/dragon-yugoslav' => [Version20261015011200::class, 'DRAGON_FACTS'],
            'sicilian/1/sveshnikov' => [Version20261015011200::class, 'SVESHNIKOV_FACTS'],
            'kings-indian/1/classical-main-line' => [Version20261015011300::class, 'CLASSICAL_FACTS'],
            'kings-indian/1/fianchetto-panno' => [Version20261015011300::class, 'PANNO_FACTS'],
            'slav/1/czech-main-line' => [Version20261015011400::class, 'CZECH_FACTS'],
            'slav/1/semi-slav-meran' => [Version20261015011400::class, 'MERAN_FACTS'],
            'italian/3/giuoco-piano-moller' => [Version20261015011500::class, 'MOLLER_FACTS'],
            'caro-kann/3/panov-development' => [Version20261015011600::class, 'PANOV_FACTS'],
            'french/3/tarrasch-chistyakov' => [Version20261015011700::class, 'TARRASCH_FACTS'],
            'slav/2/czech-main-line' => [Version20261015011800::class, 'CZECH_FACTS'],
            'scotch/1/classical-gottschall' => [Version20261016020000::class, 'CLASSICAL_GOTTSCHALL_FACTS'],
            'scotch/1/scotch-gambit-max-lange' => [Version20261016020000::class, 'SCOTCH_GAMBIT_MAX_LANGE_FACTS'],
            'four-knights/1/scotch-four-knights' => [Version20261016020100::class, 'SCOTCH_FOUR_KNIGHTS_FACTS'],
            'four-knights/1/spanish-symmetrical' => [Version20261016020100::class, 'SPANISH_SYMMETRICAL_FACTS'],
            'four-knights/1/rubinstein-maroczy' => [Version20261016020100::class, 'RUBINSTEIN_MAROCZY_FACTS'],
            'evans-gambit/1/accepted-steinitz' => [Version20261016020200::class, 'ACCEPTED_STEINITZ_FACTS'],
            'evans-gambit/1/accepted-levenfish' => [Version20261016020200::class, 'ACCEPTED_LEVENFISH_FACTS'],
            'petrov/1/classical-maroczy' => [Version20261016020300::class, 'CLASSICAL_MAROCZY_FACTS'],
            'petrov/1/classical-marshall' => [Version20261016020300::class, 'CLASSICAL_MARSHALL_FACTS'],
            'alekhine/1/four-pawns-tartakower' => [Version20261016020400::class, 'FOUR_PAWNS_TARTAKOWER_FACTS'],
            'alekhine/1/exchange-karpov' => [Version20261016020400::class, 'EXCHANGE_KARPOV_FACTS'],
            'nimzo-indian/1/rubinstein-bernstein' => [Version20261016020500::class, 'RUBINSTEIN_BERNSTEIN_FACTS'],
            'nimzo-indian/1/samisch-capablanca' => [Version20261016020500::class, 'SAMISCH_CAPABLANCA_FACTS'],
            'grunfeld/1/exchange-spassky' => [Version20261016020600::class, 'EXCHANGE_SPASSKY_FACTS'],
            'grunfeld/1/russian-keres' => [Version20261016020600::class, 'RUSSIAN_KERES_FACTS'],
            'benoni/1/classical-tal-line' => [Version20261016020700::class, 'CLASSICAL_TAL_LINE_FACTS'],
            'benoni/1/four-pawns-main-line' => [Version20261016020700::class, 'FOUR_PAWNS_MAIN_LINE_FACTS'],
            'tarrasch/1/classical-main-line' => [Version20261016020800::class, 'CLASSICAL_MAIN_LINE_FACTS'],
            'tarrasch/1/classical-advance' => [Version20261016020800::class, 'CLASSICAL_ADVANCE_FACTS'],
            'nimzo-indian/2/gligoric-isolated-pawn' => [Version20261016030000::class, 'GLIGORIC_FACTS'],
            'nimzo-indian/2/st-petersburg-fianchetto' => [Version20261016030000::class, 'PETERSBURG_FACTS'],
            'queens-indian/1/spassky-isolated-pawn' => [Version20261016030100::class, 'SPASSKY_FACTS'],
            'queens-indian/1/bogo-retreat' => [Version20261016030100::class, 'RETREAT_FACTS'],
            'grunfeld/2/modern-exchange-pawn-grab' => [Version20261016030200::class, 'PAWN_GRAB_FACTS'],
            'benoni/2/classical-argentine' => [Version20261016030300::class, 'ARGENTINE_FACTS'],
            'benoni/2/fianchetto-hastings' => [Version20261016030300::class, 'HASTINGS_FACTS'],
            'semi-slav/1/meran-wade-larsen' => [Version20261016030400::class, 'WADE_FACTS'],
            'semi-slav/1/meran-chigorin-bd6' => [Version20261016030400::class, 'CHIGORIN_FACTS'],
            'semi-slav/1/meran-early-a6' => [Version20261016030400::class, 'A6_MERAN_FACTS'],
            'semi-slav/1/botvinnik-lilienthal' => [Version20261016030400::class, 'BOTVINNIK_FACTS'],
            'semi-slav/1/anti-meran-qc2' => [Version20261016030400::class, 'ANTI_MERAN_FACTS'],
            'tarrasch/2/reti-dxc5' => [Version20261016030500::class, 'RETI_FACTS'],
            'tarrasch/2/stoltz-b6' => [Version20261016030500::class, 'STOLTZ_FACTS'],
            'slav/3/czech-main-line' => [Version20261016030600::class, 'CZECH_FACTS'],
            'slav/3/semi-slav-meran' => [Version20261016030600::class, 'MERAN_FACTS'],
            'slav/3/czech-morozevich' => [Version20261016030600::class, 'MOROZEVICH_FACTS'],
            'alekhine/2/four-pawns-korchnoi' => [Version20261016030700::class, 'KORCHNOI_FACTS'],
            'caro-kann/4/classical-seirawan' => [Version20261016030800::class, 'SEIRAWAN_FACTS'],
            'french/4/winawer-poisoned-pawn' => [Version20261016030900::class, 'POISONED_FACTS'],
            'french/4/steinitz-brodsky-jones' => [Version20261016030900::class, 'STEINITZ_FACTS'],
            'four-knights/2/classical-bardeleben' => [Version20261016031100::class, 'BARDELEBEN_FACTS'],
            'evans-gambit/2/paulsen-variation' => [Version20261016031200::class, 'PAULSEN_FACTS'],
            'evans-gambit/2/compromised-defense' => [Version20261016031200::class, 'COMPROMISED_FACTS'],
            'two-knights/1/polerio-goring' => [Version20261016031300::class, 'GORING_FACTS'],
            'two-knights/1/polerio-yankovich' => [Version20261016031300::class, 'YANKOVICH_FACTS'],
            'two-knights/1/max-lange-berger' => [Version20261016031300::class, 'BERGER_FACTS'],
            'two-knights/1/max-lange-rubinstein' => [Version20261016031300::class, 'RUBINSTEIN_FACTS'],
            'ruy-lopez/2/zaitsev-hybrid' => [Version20261016031400::class, 'ZAITSEV_FACTS'],
            'ruy-lopez/2/open-breslau' => [Version20261016031400::class, 'BRESLAU_FACTS'],
            'ruy-lopez/2/open-howell-ekstrom' => [Version20261016031400::class, 'HOWELL_FACTS'],
            'ruy-lopez/2/berlin-wall' => [Version20261016031400::class, 'BERLIN_FACTS'],
            'scotch/2/steinitz-rosenthal' => [Version20261016031500::class, 'ROSENTHAL_FACTS'],
            'scotch/2/scotch-gambit-anderssen' => [Version20261016031500::class, 'ANDERSSEN_FACTS'],
            'sicilian/2/richter-rauzer' => [Version20261016031600::class, 'RAUZER_FACTS'],
            'sicilian/2/sozin-sherbakov' => [Version20261016031600::class, 'SOZIN_FACTS'],
            'sicilian/2/scheveningen-classical' => [Version20261016031600::class, 'SCHEVENINGEN_FACTS'],
            'sicilian/2/taimanov-modern' => [Version20261016031600::class, 'TAIMANOV_FACTS'],
            'sicilian/2/alapin-endgame' => [Version20261016031600::class, 'ALAPIN_FACTS'],
            'queens-gambit/3/exchange-reshevsky' => [Version20261016031700::class, 'EXCHANGE_FACTS'],
            'queens-gambit/3/lasker-bernstein' => [Version20261016031700::class, 'BERNSTEIN_FACTS'],
            'queens-gambit/3/tartakower-defense' => [Version20261016031700::class, 'TARTAKOWER_FACTS'],
            'queens-gambit/3/cambridge-springs' => [Version20261016031700::class, 'CAMBRIDGE_FACTS'],
            'catalan/2/closed-zagoryansky' => [Version20261016031800::class, 'ZAGORYANSKY_FACTS'],
            'catalan/2/closed-spassky-gambit' => [Version20261016031800::class, 'SPASSKY_FACTS'],
            'english/1/hedgehog-flexible-formation' => [Version20261016031900::class, 'HEDGEHOG_FACTS'],
            'kings-indian/2/classical-main-line' => [Version20261016032000::class, 'CLASSICAL_FACTS'],
            'kings-indian/2/bayonet-sokolov' => [Version20261016032000::class, 'BAYONET_FACTS'],
            'kings-indian/2/petrosian-keres' => [Version20261016032000::class, 'PETROSIAN_FACTS'],
            'kings-indian/2/positional-defense' => [Version20261016032000::class, 'POSITIONAL_FACTS'],
            'kings-indian/2/samisch-bronstein' => [Version20261016032000::class, 'SAMISCH_FACTS'],
        ];
        foreach ($constants as $id => [$class, $constant]) {
            /** @var list<array{uci: string, san: string, fromKey: string, toKey: string}> $facts */
            $facts = (new \ReflectionClass($class))->getConstant($constant);
            if (!is_array($facts)) {
                throw new \LogicException('The frozen lesson constant is missing: '.$id);
            }
            $paths[$id] = $facts;
        }

        return $paths;
    }
}
