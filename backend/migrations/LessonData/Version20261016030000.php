<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Nimzo-Indian Defense revision 2: keeps both revision 1 lines and adds two complete named pinned paths. */
final class Version20261016030000 extends AbstractMigration
{
    public const GLIGORIC_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. e3 O-O 5. Bd3 d5 6. Nf3 c5 7. O-O Nc6 8. a3 dxc4 9. Bxc4 cxd4';
    public const PETERSBURG_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. e3 b6 5. Bd3 Bb7 6. Nf3 O-O 7. O-O c5 8. Na4 cxd4 9. a3 Be7';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['rubinstein-bernstein' => [21, 0], 'samisch-capablanca' => [18, 1]];

    public const GLIGORIC_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbq1rk1/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR w KQ -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'rnbq1rk1/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR w KQ -', 'toKey' => 'rnbq1rk1/pppp1ppp/4pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR b KQ -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbq1rk1/pppp1ppp/4pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR b KQ -', 'toKey' => 'rnbq1rk1/ppp2ppp/4pn2/3p4/1bPP4/2NBP3/PP3PPP/R1BQK1NR w KQ -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbq1rk1/ppp2ppp/4pn2/3p4/1bPP4/2NBP3/PP3PPP/R1BQK1NR w KQ -', 'toKey' => 'rnbq1rk1/ppp2ppp/4pn2/3p4/1bPP4/2NBPN2/PP3PPP/R1BQK2R b KQ -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbq1rk1/ppp2ppp/4pn2/3p4/1bPP4/2NBPN2/PP3PPP/R1BQK2R b KQ -', 'toKey' => 'rnbq1rk1/pp3ppp/4pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/pp3ppp/4pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQK2R w KQ -', 'toKey' => 'rnbq1rk1/pp3ppp/4pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbq1rk1/pp3ppp/4pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 w - -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2pp4/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2pp4/1bPP4/P1NBPN2/1P3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'd5c4', 'san' => 'dxc4', 'fromKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2pp4/1bPP4/P1NBPN2/1P3PPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2p5/1bpP4/P1NBPN2/1P3PPP/R1BQ1RK1 w - -'],
        ['uci' => 'd3c4', 'san' => 'Bxc4', 'fromKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2p5/1bpP4/P1NBPN2/1P3PPP/R1BQ1RK1 w - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2p5/1bBP4/P1N1PN2/1P3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'r1bq1rk1/pp3ppp/2n1pn2/2p5/1bBP4/P1N1PN2/1P3PPP/R1BQ1RK1 b - -', 'toKey' => 'r1bq1rk1/pp3ppp/2n1pn2/8/1bBp4/P1N1PN2/1P3PPP/R1BQ1RK1 w - -'],
    ];

    public const PETERSBURG_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/2N5/PP2PPPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N5/PP2PPPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N5/PP2PPPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'rnbqk2r/pppp1ppp/4pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/p1pp1ppp/1p2pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'rnbqk2r/p1pp1ppp/1p2pn2/8/1bPP4/2N1P3/PP3PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/p1pp1ppp/1p2pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR b KQkq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'rnbqk2r/p1pp1ppp/1p2pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR b KQkq -', 'toKey' => 'rn1qk2r/pbpp1ppp/1p2pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rn1qk2r/pbpp1ppp/1p2pn2/8/1bPP4/2NBP3/PP3PPP/R1BQK1NR w KQkq -', 'toKey' => 'rn1qk2r/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rn1qk2r/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rn1q1rk1/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rn1q1rk1/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQK2R w KQ -', 'toKey' => 'rn1q1rk1/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rn1q1rk1/pbpp1ppp/1p2pn2/8/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 b - -', 'toKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/2p5/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 w - -'],
        ['uci' => 'c3a4', 'san' => 'Na4', 'fromKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/2p5/1bPP4/2NBPN2/PP3PPP/R1BQ1RK1 w - -', 'toKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/2p5/NbPP4/3BPN2/PP3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/2p5/NbPP4/3BPN2/PP3PPP/R1BQ1RK1 b - -', 'toKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/8/NbPp4/3BPN2/PP3PPP/R1BQ1RK1 w - -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/8/NbPp4/3BPN2/PP3PPP/R1BQ1RK1 w - -', 'toKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/8/NbPp4/P2BPN2/1P3PPP/R1BQ1RK1 b - -'],
        ['uci' => 'b4e7', 'san' => 'Be7', 'fromKey' => 'rn1q1rk1/pb1p1ppp/1p2pn2/8/NbPp4/P2BPN2/1P3PPP/R1BQ1RK1 b - -', 'toKey' => 'rn1q1rk1/pb1pbppp/1p2pn2/8/N1Pp4/P2BPN2/1P3PPP/R1BQ1RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Nimzo-Indian Defense revision 2: add complete named lines (gligoric-isolated-pawn, st-petersburg-fianchetto)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play Black against 1. d4 and 2. c4. After ...Nf6 and ...e6, you answer Nc3 with ...Bb4 and pin the knight that supports e2-e4. You are ready to give up that bishop for the knight to double White\'s c-pawns; in return White gets the bishop pair. All four lines meet White\'s quiet e3 setup. In the Bernstein Defense you castle, claim the centre with ...d5, take on c3 and c4, then develop with ...c5, ...Nc6 and ...Qc7. In the Capablanca Variation you hit d4 with ...c5 and ...Nc6, prepare ...Ba6 against c4 and answer White\'s e4 with ...Ne8. In the Gligoric System you take on c4 and d4 before deciding about your bishop. In the St. Petersburg Variation you fianchetto with ...b6 and ...Bb7. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['gligoric-isolated-pawn', 'Gligoric System: ...dxc4 and ...cxd4 before deciding about the bishop', 'Nine Black moves. Castle, play ...d5, ...c5 and ...Nc6 while White develops and castles, then meet a3 by taking on c4 and d4 instead of on c3, keeping your bishop for now. Source: Normal Variation, Gligoric System, Bernstein Defense.', 2, self::GLIGORIC_SOURCE, self::GLIGORIC_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight to f6, where it controls e4 and d5. White cannot play e2-e4 yet without losing the pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Open the diagonal for the bishop on f8 and add control of d5. The bishop can now develop actively, for example to b4.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bb4', 'Move your bishop from f8 to b4.', 'This is the Nimzo-Indian. Pin the knight on c3 against White\'s king: while the pin lasts, the knight cannot recapture on e4, so White needs more preparation before playing e2-e4. You are also ready to give up the bishop for this knight if that doubles White\'s c-pawns.'],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 with a pawn and opens the diagonal for the bishop on f1. This quiet setup prepares Bd3 and kingside castling.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle first: this line follows the Bernstein line until White\'s sixth move. Your king is safe, the rook comes to f8, and you keep your central pawn moves for later.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop to d3, where it guards c4, adds control of e4 and aims along the b1-h7 diagonal towards h7. Your king and the knight on f6 defend h7.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Claim a share of the centre. Your pawn on d5 attacks c4 and adds a second guard on e4, together with the knight on f6.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, defends d4 and gets ready to castle. Your bishop still pins the knight on c3.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Hit d4 with the c-pawn. Your pawns now attack both d4 and c4, and the c5 pawn also protects your bishop on b4.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles. The king has left e1, so the knight on c3 is no longer pinned, although your bishop still attacks it; the pawn on b2 defends it.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight to its natural square. It adds a second attacker to d4 and protects your bishop on b4 a second time.'],
                ['a3', 'Move your pawn from a2 to a3.', 'White attacks your bishop with the a-pawn and puts the question to it. You do not have to take on c3 or retreat at once: your next move creates a counter-attack first.'],
                ['dxc4', 'Move your pawn from d5 to c4, capturing the white pawn.', 'Before deciding about the bishop, take on c4. Your pawn now attacks White\'s bishop on d3, so if White takes your bishop with axb4, you can take White\'s bishop with ...cxd3. With the d-file half-open, your queen also joins the c5 pawn and the knight in attacking d4.'],
                ['Bxc4', 'Move your bishop from d3 to c4, capturing the black pawn.', 'White recaptures and material is level again. The d4 pawn is attacked three times and defended three times: by the e3 pawn, the knight on f3 and the queen.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Take on d4. Your pawn there attacks the knight on c3 and the pawn on e3, while White\'s a3 pawn still attacks your bishop. The named Gligoric System line ends here. If White recaptures with the e-pawn, the pawn on d4 is isolated: your next tasks are to bring the bishop to safety, put a piece on d5 in front of that pawn and build pressure against it.'],
            ]],
            ['st-petersburg-fianchetto', 'St. Petersburg Variation: ...b6, ...Bb7 and ...cxd4', 'Nine Black moves. Fianchetto your queen\'s bishop with ...b6 and ...Bb7 to fight for e4, castle, strike with ...c5, then meet Na4 and a3 by taking on d4 and retreating the bishop to e7. Source: St. Petersburg Variation.', 3, self::PETERSBURG_SOURCE, self::PETERSBURG_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a diagonal for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop the knight to f6, where it controls e4 and d5. White cannot play e2-e4 yet without losing the pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White gains space on the queenside and adds control of d5.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Open the diagonal for the bishop on f8 and add control of d5. The bishop can now develop actively, for example to b4.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the knight, controls d5 and e4, and prepares to play e2-e4.'],
                ['Bb4', 'Move your bishop from f8 to b4.', 'This is the Nimzo-Indian. Pin the knight on c3 against White\'s king: while the pin lasts, the knight cannot recapture on e4, so White needs more preparation before playing e2-e4. You are also ready to give up the bishop for this knight if that doubles White\'s c-pawns.'],
                ['e3', 'Move your pawn from e2 to e3.', 'White supports d4 with a pawn and opens the diagonal for the bishop on f1. This quiet setup prepares Bd3 and kingside castling.'],
                ['b6', 'Move your pawn from b7 to b6.', 'This is the St. Petersburg Variation. Prepare to fianchetto your queen\'s bishop on b7, where it will add to your control of e4 together with the knight on f6. Your central pawns stay flexible.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop towards your kingside, where it aims at h7. It also defends the c4 pawn and adds control of e4.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Develop the bishop on the long diagonal. It controls e4 together with your knight, and it eyes the g2 pawn, which nothing defends at the moment.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight, defends d4 and blocks the long diagonal in front of g2. Your bishop now presses on the knight on f3, which the g2 pawn and the queen defend.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle. Your king is safe, and you can now decide how to challenge White\'s centre: with ...d5, ...c5 or both.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles too. The knight on c3 is no longer pinned to the king, but your bishop still attacks it and the pawn on b2 defends it.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Strike at d4 with the c-pawn. Your pawn on c5 is protected by the b6 pawn and the bishop on b4, and it attacks d4, which the e3 pawn and the knight on f3 defend.'],
                ['Na4', 'Move your knight from c3 to a4.', 'White moves the knight away from c3, so you can no longer double White\'s pawns by taking there. From a4 the knight attacks your pawns on c5 and b6.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Release the tension by taking on d4. This removes the c5 pawn that the knight attacked and gives you the half-open c-file, where a rook can later press on c4. For the moment you are a pawn up, but your d4 pawn is attacked twice and undefended.'],
                ['a3', 'Move your pawn from a2 to a3.', 'Before recapturing, White attacks your bishop on b4, which nothing defends now that the c5 pawn has gone.'],
                ['Be7', 'Move your bishop from b4 to e7.', 'Retreat the bishop to e7, where it defends your knight on f6. The named St. Petersburg Variation ends here: White is ready to win back the pawn on d4, and your next tasks are to develop the knight on b8, use the half-open c-file against c4 and keep your bishop on b7 aimed at e4 and the knight on f3.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='nimzo-indian'")) {
            throw new \RuntimeException('Nimzo-Indian Defense revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='nimzo-indian' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Nimzo-Indian Defense revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Nimzo-Indian Defense teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='nimzo-indian' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='nimzo-indian' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='nimzo-indian' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('nimzo-indian',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('nimzo-indian',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Nimzo-Indian Defense edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='nimzo-indian'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Nimzo-Indian Defense sessions and progress.');
    }
}
