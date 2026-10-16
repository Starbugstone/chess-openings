<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** New Queen's Indian and Bogo-Indian course: complete named pinned paths with original teaching notes. */
final class Version20261016030100 extends AbstractMigration
{
    public const SPASSKY_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nf3 b6 4. e3 Bb7 5. Bd3 c5 6. Nc3 cxd4 7. exd4 Be7 8. O-O d5 9. cxd5 Nxd5';
    public const RETREAT_SOURCE = '1. d4 Nf6 2. c4 e6 3. Nf3 d5 4. g3 Bb4+ 5. Bd2 Be7 6. Bg2 O-O 7. O-O c6 8. Qc2 Nbd7 9. Bf4 b6 10. Nbd2 Ba6';

    public const SPASSKY_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'e2e3', 'san' => 'e3', 'fromKey' => 'rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/4PN2/PP3PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'c8b7', 'san' => 'Bb7', 'fromKey' => 'rnbqkb1r/p1pp1ppp/1p2pn2/8/2PP4/4PN2/PP3PPP/RNBQKB1R b KQkq -', 'toKey' => 'rn1qkb1r/pbpp1ppp/1p2pn2/8/2PP4/4PN2/PP3PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1d3', 'san' => 'Bd3', 'fromKey' => 'rn1qkb1r/pbpp1ppp/1p2pn2/8/2PP4/4PN2/PP3PPP/RNBQKB1R w KQkq -', 'toKey' => 'rn1qkb1r/pbpp1ppp/1p2pn2/8/2PP4/3BPN2/PP3PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rn1qkb1r/pbpp1ppp/1p2pn2/8/2PP4/3BPN2/PP3PPP/RNBQK2R b KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2PP4/3BPN2/PP3PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2PP4/3BPN2/PP3PPP/RNBQK2R w KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/2p5/2PP4/2NBPN2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/8/2Pp4/2NBPN2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e3d4', 'san' => 'exd4', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/8/2Pp4/2NBPN2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQK2R b KQkq -'],
        ['uci' => 'f8e7', 'san' => 'Be7', 'fromKey' => 'rn1qkb1r/pb1p1ppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQK2R b KQkq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQK2R w KQkq -', 'toKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rn1qk2r/pb1pbppp/1p2pn2/8/2PP4/2NB1N2/PP3PPP/R1BQ1RK1 b kq -', 'toKey' => 'rn1qk2r/pb2bppp/1p2pn2/3p4/2PP4/2NB1N2/PP3PPP/R1BQ1RK1 w kq -'],
        ['uci' => 'c4d5', 'san' => 'cxd5', 'fromKey' => 'rn1qk2r/pb2bppp/1p2pn2/3p4/2PP4/2NB1N2/PP3PPP/R1BQ1RK1 w kq -', 'toKey' => 'rn1qk2r/pb2bppp/1p2pn2/3P4/3P4/2NB1N2/PP3PPP/R1BQ1RK1 b kq -'],
        ['uci' => 'f6d5', 'san' => 'Nxd5', 'fromKey' => 'rn1qk2r/pb2bppp/1p2pn2/3P4/3P4/2NB1N2/PP3PPP/R1BQ1RK1 b kq -', 'toKey' => 'rn1qk2r/pb2bppp/1p2p3/3n4/3P4/2NB1N2/PP3PPP/R1BQ1RK1 w kq -'],
    ];

    public const RETREAT_FACTS = [
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'c2c4', 'san' => 'c4', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkb1r/pppppppp/5n2/8/2PP4/8/PP2PPPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/8/PP2PPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkb1r/pppp1ppp/4pn2/8/2PP4/5N2/PP2PPPP/RNBQKB1R b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -'],
        ['uci' => 'g2g3', 'san' => 'g3', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5N2/PP2PPPP/RNBQKB1R w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4+', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/2PP4/5NP1/PP2PP1P/RNBQKB1R b KQkq -', 'toKey' => 'rnbqk2r/ppp2ppp/4pn2/3p4/1bPP4/5NP1/PP2PP1P/RNBQKB1R w KQkq -'],
        ['uci' => 'c1d2', 'san' => 'Bd2', 'fromKey' => 'rnbqk2r/ppp2ppp/4pn2/3p4/1bPP4/5NP1/PP2PP1P/RNBQKB1R w KQkq -', 'toKey' => 'rnbqk2r/ppp2ppp/4pn2/3p4/1bPP4/5NP1/PP1BPP1P/RN1QKB1R b KQkq -'],
        ['uci' => 'b4e7', 'san' => 'Be7', 'fromKey' => 'rnbqk2r/ppp2ppp/4pn2/3p4/1bPP4/5NP1/PP1BPP1P/RN1QKB1R b KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPP1P/RN1QKB1R w KQkq -'],
        ['uci' => 'f1g2', 'san' => 'Bg2', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPP1P/RN1QKB1R w KQkq -', 'toKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1QK2R b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'rnbqk2r/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1QK2R b KQkq -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1QK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1QK2R w KQ -', 'toKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1Q1RK1 b - -'],
        ['uci' => 'c7c6', 'san' => 'c6', 'fromKey' => 'rnbq1rk1/ppp1bppp/4pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1Q1RK1 b - -', 'toKey' => 'rnbq1rk1/pp2bppp/2p1pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1Q1RK1 w - -'],
        ['uci' => 'd1c2', 'san' => 'Qc2', 'fromKey' => 'rnbq1rk1/pp2bppp/2p1pn2/3p4/2PP4/5NP1/PP1BPPBP/RN1Q1RK1 w - -', 'toKey' => 'rnbq1rk1/pp2bppp/2p1pn2/3p4/2PP4/5NP1/PPQBPPBP/RN3RK1 b - -'],
        ['uci' => 'b8d7', 'san' => 'Nbd7', 'fromKey' => 'rnbq1rk1/pp2bppp/2p1pn2/3p4/2PP4/5NP1/PPQBPPBP/RN3RK1 b - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQBPPBP/RN3RK1 w - -'],
        ['uci' => 'd2f4', 'san' => 'Bf4', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP4/5NP1/PPQBPPBP/RN3RK1 w - -', 'toKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP1B2/5NP1/PPQ1PPBP/RN3RK1 b - -'],
        ['uci' => 'b7b6', 'san' => 'b6', 'fromKey' => 'r1bq1rk1/pp1nbppp/2p1pn2/3p4/2PP1B2/5NP1/PPQ1PPBP/RN3RK1 b - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP1B2/5NP1/PPQ1PPBP/RN3RK1 w - -'],
        ['uci' => 'b1d2', 'san' => 'Nbd2', 'fromKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP1B2/5NP1/PPQ1PPBP/RN3RK1 w - -', 'toKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP1B2/5NP1/PPQNPPBP/R4RK1 b - -'],
        ['uci' => 'c8a6', 'san' => 'Ba6', 'fromKey' => 'r1bq1rk1/p2nbppp/1pp1pn2/3p4/2PP1B2/5NP1/PPQNPPBP/R4RK1 b - -', 'toKey' => 'r2q1rk1/p2nbppp/bpp1pn2/3p4/2PP1B2/5NP1/PPQNPPBP/R4RK1 w - -'],
    ];

    public function getDescription(): string
    {
        return "Publish the Queen's Indian and Bogo-Indian course: Queen's Indian Spassky System, Bogo-Indian Retreat Variation";
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = "Play Black against 1. d4, 2. c4 and 3. Nf3. With the knight on f3 rather than c3, the Nimzo-Indian pin is not available, so you fight for the light central squares in other ways. In the Queen's Indian Defense against 4. e3 you fianchetto with ...b6 and ...Bb7, exchange on d4 with ...c5 and then break with ...d5, leaving White an isolated d-pawn. In the Bogo-Indian Retreat Variation you answer 3. Nf3 with ...d5, meet White's Catalan set-up with the check ...Bb4+, retreat the bishop to e7 once White's bishop has gone to d2, and later aim at c4 with ...b6 and ...Ba6. White replies automatically along the selected line; real opponents can choose other continuations.";
        /** @var list<array{string, string, string, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['spassky-isolated-pawn', "Queen's Indian, Spassky System: ...Bb7, ...cxd4 and ...d5", "Nine Black moves. Fianchetto with ...b6 and ...Bb7, exchange your c-pawn for White's d-pawn, then break with ...d5 and recapture with the knight, which leaves White an isolated pawn on d4 and your knight on the square in front of it. Source: Queen's Indian Defense: Spassky System3 (exact catalogue name).", self::SPASSKY_SOURCE, self::SPASSKY_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It controls e4, so White cannot play e2-e4 for now without giving up a pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White adds a second pawn to the fight for the centre. The c-pawn controls d5 and gains space on the queenside.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Open the diagonal of your bishop on f8 and prepare ...d5. If White develops the knight to c3, your bishop can pin it from b4: that is the Nimzo-Indian Defense.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the other knight instead, so your bishop has no knight on c3 to pin. The knight on f3 defends d4 and controls e5.'],
                ['b6', 'Move your pawn from b7 to b6.', "This is the Queen's Indian Defense. You prepare to fianchetto your queen's bishop on b7, where it will fight for the light squares in the centre, especially e4, together with your knight on f6."],
                ['e3', 'Move your pawn from e2 to e3.', 'White chooses a quiet set-up. The pawn on e3 supports d4 and opens the diagonal of the bishop on f1; the bishop on c1 stays behind the pawn for now.'],
                ['Bb7', 'Move your bishop from c8 to b7.', 'Develop the bishop on the long diagonal. It controls d5 and e4 and already presses on the knight on f3, which the g2 pawn and the queen defend.'],
                ['Bd3', 'Move your bishop from f1 to d3.', 'White develops the bishop to d3, where it adds control of e4, defends the c4 pawn and aims at h7.'],
                ['c5', 'Move your pawn from c7 to c5.', "Challenge d4 with the c-pawn, supported by the b6 pawn and your bishop on f8. You are ready to exchange it for White's d-pawn and open the c-file."],
                ['Nc3', 'Move your knight from b1 to c3.', 'White develops the second knight. It controls d5 and e4, the squares your bishop on b7 is fighting for.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Exchange on d4. Your pawn there now attacks both the knight on c3 and the pawn on e3, so White recaptures at once.'],
                ['exd4', 'Move your pawn from e3 to d4, capturing the black pawn.', 'White recaptures with the e-pawn. Material is level, White has pawns on c4 and d4, and the bishop on c1 now has an open diagonal.'],
                ['Be7', 'Move your bishop from f8 to e7.', "Develop the bishop to e7 and prepare to castle. From e7 it protects your knight on f6, which matters if White's bishop comes to g5."],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles. No white pawn stands on the e-file any more, so a white rook can use it from e1.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Strike in the centre. Your pawn attacks c4, which only the bishop on d3 defends, and d5 is supported by your queen, the bishop on b7, the e6 pawn and the knight on f6.'],
                ['cxd5', 'Move your pawn from c4 to d5, capturing the black pawn.', 'White takes on d5. For a moment White is a pawn up, and the pawn on d5 attacks your e6 pawn, which the f7 pawn defends.'],
                ['Nxd5', 'Move your knight from f6 to d5, capturing the white pawn.', "Recapture with the knight. White's d4 pawn now has no c- or e-pawn beside it: it is isolated, and no white pawn can drive your knight from d5, the square in front of it. The knight also attacks the knight on c3. The named Spassky System line ends here: your next tasks are to castle, develop the knight on b8 and build pressure against d4."],
            ]],
            ['bogo-retreat', 'Bogo-Indian Retreat: ...Bb4+, ...Be7 and ...Ba6 against the Catalan set-up', 'Ten Black moves. Answer 3. Nf3 with ...d5, check with ...Bb4+ when White fianchettoes, retreat the bishop to e7 after Bd2, build a solid ...c6 and ...Nbd7 centre, then aim your bishop at c4 with ...b6 and ...Ba6. Source: Bogo-Indian Defense: Retreat Variation.', self::RETREAT_SOURCE, self::RETREAT_FACTS, [
                ['d4', 'Move your pawn from d2 to d4.', 'White takes space in the centre and opens a path for the bishop on c1.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop a knight towards the centre. It controls e4, so White cannot play e2-e4 for now without giving up a pawn.'],
                ['c4', 'Move your pawn from c2 to c4.', 'White adds a second pawn to the fight for the centre. The c-pawn controls d5 and gains space on the queenside.'],
                ['e6', 'Move your pawn from e7 to e6.', 'Open the diagonal of your bishop on f8 and prepare ...d5. If White develops the knight to c3, your bishop can pin it from b4: that is the Nimzo-Indian Defense.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the other knight instead, so your bishop has no knight on c3 to pin. The knight on f3 defends d4 and controls e5.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Answer with ...d5 and take a firm share of the centre. The pawn is supported by your e6 pawn, knight and queen, and it attacks c4, which nothing defends for the moment.'],
                ['g3', 'Move your pawn from g2 to g3.', 'White prepares to fianchetto the bishop on g2: this is the Catalan set-up. From g2 the bishop will aim along the long diagonal at d5 and your queenside.'],
                ['Bb4+', 'Move your bishop from f8 to b4, giving check.', "Check with the bishop: this gives the line its Bogo-Indian name. White must answer it before completing the fianchetto, and blocking with the bishop on d2 puts it on the square that White's knight on b1 would like to use."],
                ['Bd2', 'Move your bishop from c1 to d2.', "White blocks with the bishop. Your bishop on b4 is now attacked and nothing defends it, and it pins White's bishop to the king."],
                ['Be7', 'Move your bishop from b4 to e7.', "This is the Retreat Variation: instead of exchanging bishops, retreat to e7. You have spent an extra move on your bishop, but White's bishop now stands on d2, in the way of the knight on b1. In this line White later spends a move taking it to f4, so the time evens out."],
                ['Bg2', 'Move your bishop from f1 to g2.', 'White completes the fianchetto. For now the bishop stands behind its own knight on f3; once the knight moves, it will aim down the long diagonal at your pawn on d5.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle and put your king in safety. Your centre stands firm: the d5 pawn is attacked once and defended three times.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'White castles and leaves the c4 pawn undefended for now: if you took it, White would try to win it back with pieces. In this line you keep your pawn on d5 instead.'],
                ['c6', 'Move your pawn from c7 to c6.', 'Support d5 with a second pawn. Your pawns on c6, d5 and e6 now form a solid triangle, and the d5 pawn, which stands on the long diagonal of the bishop on g2, is firmly protected.'],
                ['Qc2', 'Move your queen from d1 to c2.', "White defends c4 with the queen and eyes h7. From c2 the queen also supports a later e2-e4, White's natural way to challenge your centre."],
                ['Nbd7', 'Move your knight from b8 to d7.', 'Develop the knight behind your pawns. From d7 it supports the knight on f6 and controls c5 and e5, so it can help a later ...c5 or ...e5.'],
                ['Bf4', 'Move your bishop from d2 to f4.', 'White moves the bishop again, from d2 to f4, where it controls e5 and the b8-h2 diagonal and frees d2 for the knight. This is now the same position as in the Closed Catalan, where Black develops the bishop to e7 without the check.'],
                ['b6', 'Move your pawn from b7 to b6.', 'Prepare to develop your last minor piece. The bishop on c8 is shut in by your pawn on b7 and the knight on d7, so ...b6 opens the b7 and a6 squares for it.'],
                ['Nbd2', 'Move your knight from b1 to d2.', 'White develops the last knight. From d2 it defends c4 a second time and supports e2-e4.'],
                ['Ba6', 'Move your bishop from c8 to a6.', "Develop the bishop to a6, where it attacks c4 together with your d5 pawn; White's queen and knight defend it. The named Retreat Variation ends here: your next tasks are to keep the pressure on c4, prepare ...c5, supported by ...Rc8, to free your position, and be ready for White's e2-e4."],
            ]],
        ];

        if (false !== $this->connection->fetchOne("SELECT id FROM lesson WHERE id='queens-indian'")) {
            throw new \RuntimeException("The Queen's Indian and Bogo-Indian course already exists.");
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException("Queen's Indian and Bogo-Indian teaching text and frozen path disagree: ".$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }
        $start = $this->connection->fetchOne('SELECT id FROM chess_position WHERE position_key=?', [self::SPASSKY_FACTS[0]['fromKey']]);
        if (false === $start) {
            throw new \RuntimeException("The imported Queen's Indian and Bogo-Indian start position is missing.");
        }

        $this->addSql("INSERT INTO lesson (id,current_revision,sort_order) VALUES ('queens-indian',NULL,19)");
        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) VALUES ('queens-indian',1,?,?,?,UTC_TIMESTAMP(),'b')", ["Queen's Indian and Bogo-Indian", $introduction, (int) $start]);
        foreach ($lines as $index => [$lineId, $title, $description, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('queens-indian',1,?,?,?,?,?)", [$lineId, $title, $description, $index, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('queens-indian',1,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException("Missing resolved Queen's Indian and Bogo-Indian edge."), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=1 WHERE id='queens-indian'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException("Publish a new revision; preserve Queen's Indian and Bogo-Indian sessions and progress.");
    }
}
