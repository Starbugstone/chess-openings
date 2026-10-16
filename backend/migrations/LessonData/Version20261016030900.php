<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** French revision 4: keeps all three revision 3 lines and adds the complete Winawer Poisoned Pawn and Steinitz Brodsky-Jones records. */
final class Version20261016030900 extends AbstractMigration
{
    public const POISONED_SOURCE = '1. e4 e6 2. d4 d5 3. Nc3 Bb4 4. e5 c5 5. a3 Bxc3+ 6. bxc3 Ne7 7. Qg4 Qc7 8. Qxg7 Rg8 9. Qxh7 cxd4 10. Ne2';
    public const STEINITZ_SOURCE = '1. e4 e6 2. d4 d5 3. Nc3 Nf6 4. e5 Nfd7 5. f4 c5 6. dxc5 Nc6 7. a3 Bxc5 8. Qg4 O-O 9. Nf3 f6';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['classical-stahlberg' => [20, 0], 'winawer-positional' => [18, 1], 'tarrasch-chistyakov' => [20, 2]];

    public const POISONED_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk1nr/ppp2ppp/4p3/3p4/1b1PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'rnbqk1nr/ppp2ppp/4p3/3p4/1b1PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk1nr/ppp2ppp/4p3/3pP3/1b1P4/2N5/PPP2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqk1nr/ppp2ppp/4p3/3pP3/1b1P4/2N5/PPP2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/1b1P4/2N5/PPP2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/1b1P4/2N5/PPP2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/1b1P4/P1N5/1PP2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'b4c3', 'san' => 'Bxc3+', 'fromKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/1b1P4/P1N5/1PP2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/3P4/P1b5/1PP2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'b2c3', 'san' => 'bxc3', 'fromKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/3P4/P1b5/1PP2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8e7', 'san' => 'Ne7', 'fromKey' => 'rnbqk1nr/pp3ppp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqk2r/pp2nppp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'd1g4', 'san' => 'Qg4', 'fromKey' => 'rnbqk2r/pp2nppp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqk2r/pp2nppp/4p3/2ppP3/3P2Q1/P1P5/2P2PPP/R1B1KBNR b KQkq -'],
        ['uci' => 'd8c7', 'san' => 'Qc7', 'fromKey' => 'rnbqk2r/pp2nppp/4p3/2ppP3/3P2Q1/P1P5/2P2PPP/R1B1KBNR b KQkq -', 'toKey' => 'rnb1k2r/ppq1nppp/4p3/2ppP3/3P2Q1/P1P5/2P2PPP/R1B1KBNR w KQkq -'],
        ['uci' => 'g4g7', 'san' => 'Qxg7', 'fromKey' => 'rnb1k2r/ppq1nppp/4p3/2ppP3/3P2Q1/P1P5/2P2PPP/R1B1KBNR w KQkq -', 'toKey' => 'rnb1k2r/ppq1npQp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR b KQkq -'],
        ['uci' => 'h8g8', 'san' => 'Rg8', 'fromKey' => 'rnb1k2r/ppq1npQp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR b KQkq -', 'toKey' => 'rnb1k1r1/ppq1npQp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR w KQq -'],
        ['uci' => 'g7h7', 'san' => 'Qxh7', 'fromKey' => 'rnb1k1r1/ppq1npQp/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR w KQq -', 'toKey' => 'rnb1k1r1/ppq1np1Q/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR b KQq -'],
        ['uci' => 'c5d4', 'san' => 'cxd4', 'fromKey' => 'rnb1k1r1/ppq1np1Q/4p3/2ppP3/3P4/P1P5/2P2PPP/R1B1KBNR b KQq -', 'toKey' => 'rnb1k1r1/ppq1np1Q/4p3/3pP3/3p4/P1P5/2P2PPP/R1B1KBNR w KQq -'],
        ['uci' => 'g1e2', 'san' => 'Ne2', 'fromKey' => 'rnb1k1r1/ppq1np1Q/4p3/3pP3/3p4/P1P5/2P2PPP/R1B1KBNR w KQq -', 'toKey' => 'rnb1k1r1/ppq1np1Q/4p3/3pP3/3p4/P1P5/2P1NPPP/R1B1KB1R b KQq -'],
    ];

    public const STEINITZ_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e6', 'san' => 'e6', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/ppp2ppp/4pn2/3pP3/3P4/2N5/PPP2PPP/R1BQKBNR b KQkq -'],
        ['uci' => 'f6d7', 'san' => 'Nfd7', 'fromKey' => 'rnbqkb1r/ppp2ppp/4pn2/3pP3/3P4/2N5/PPP2PPP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pppn1ppp/4p3/3pP3/3P4/2N5/PPP2PPP/R1BQKBNR w KQkq -'],
        ['uci' => 'f2f4', 'san' => 'f4', 'fromKey' => 'rnbqkb1r/pppn1ppp/4p3/3pP3/3P4/2N5/PPP2PPP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pppn1ppp/4p3/3pP3/3P1P2/2N5/PPP3PP/R1BQKBNR b KQkq -'],
        ['uci' => 'c7c5', 'san' => 'c5', 'fromKey' => 'rnbqkb1r/pppn1ppp/4p3/3pP3/3P1P2/2N5/PPP3PP/R1BQKBNR b KQkq -', 'toKey' => 'rnbqkb1r/pp1n1ppp/4p3/2ppP3/3P1P2/2N5/PPP3PP/R1BQKBNR w KQkq -'],
        ['uci' => 'd4c5', 'san' => 'dxc5', 'fromKey' => 'rnbqkb1r/pp1n1ppp/4p3/2ppP3/3P1P2/2N5/PPP3PP/R1BQKBNR w KQkq -', 'toKey' => 'rnbqkb1r/pp1n1ppp/4p3/2PpP3/5P2/2N5/PPP3PP/R1BQKBNR b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkb1r/pp1n1ppp/4p3/2PpP3/5P2/2N5/PPP3PP/R1BQKBNR b KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2n1p3/2PpP3/5P2/2N5/PPP3PP/R1BQKBNR w KQkq -'],
        ['uci' => 'a2a3', 'san' => 'a3', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2n1p3/2PpP3/5P2/2N5/PPP3PP/R1BQKBNR w KQkq -', 'toKey' => 'r1bqkb1r/pp1n1ppp/2n1p3/2PpP3/5P2/P1N5/1PP3PP/R1BQKBNR b KQkq -'],
        ['uci' => 'f8c5', 'san' => 'Bxc5', 'fromKey' => 'r1bqkb1r/pp1n1ppp/2n1p3/2PpP3/5P2/P1N5/1PP3PP/R1BQKBNR b KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2n1p3/2bpP3/5P2/P1N5/1PP3PP/R1BQKBNR w KQkq -'],
        ['uci' => 'd1g4', 'san' => 'Qg4', 'fromKey' => 'r1bqk2r/pp1n1ppp/2n1p3/2bpP3/5P2/P1N5/1PP3PP/R1BQKBNR w KQkq -', 'toKey' => 'r1bqk2r/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N5/1PP3PP/R1B1KBNR b KQkq -'],
        ['uci' => 'e8g8', 'san' => 'O-O', 'fromKey' => 'r1bqk2r/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N5/1PP3PP/R1B1KBNR b KQkq -', 'toKey' => 'r1bq1rk1/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N5/1PP3PP/R1B1KBNR w KQ -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'r1bq1rk1/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N5/1PP3PP/R1B1KBNR w KQ -', 'toKey' => 'r1bq1rk1/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N2N2/1PP3PP/R1B1KB1R b KQ -'],
        ['uci' => 'f7f6', 'san' => 'f6', 'fromKey' => 'r1bq1rk1/pp1n1ppp/2n1p3/2bpP3/5PQ1/P1N2N2/1PP3PP/R1B1KB1R b KQ -', 'toKey' => 'r1bq1rk1/pp1n2pp/2n1pp2/2bpP3/5PQ1/P1N2N2/1PP3PP/R1B1KB1R w KQ -'],
    ];

    public function getDescription(): string
    {
        return 'Publish French revision 4: add the complete Winawer Poisoned Pawn and Steinitz Brodsky-Jones lines';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $opening = [
            ['e4', 'Move your pawn from e2 to e4.', 'White takes space in the centre and opens a diagonal for the bishop on f1.'],
            ['e6', 'Move your pawn from e7 to e6.', 'Prepare to put the d-pawn on d5 with support from this pawn. This is the first move of the French Defense.'],
            ['d4', 'Move your pawn from d2 to d4.', 'White builds a broad pawn centre. Black will challenge it rather than let both pawns advance freely.'],
            ['d5', 'Move your pawn from d7 to d5.', 'Challenge the pawn on e4. Your pawn on e6 supports d5, so White cannot simply win that pawn by capturing it.'],
            ['Nc3', 'Move your knight from b1 to c3.', 'White defends e4 with a knight and keeps the pawn tension.'],
        ];
        $introduction = 'Play Black against 1. e4 with ...e6 and ...d5, then challenge White\'s centre, usually with ...c5. Play five complete named lines from the opening reference: the Classical Variation against 3. Nc3 Nf6, with castling on opposite sides; the Winawer against 3. Nc3 Bb4, where you pin and exchange the c3 knight to weaken White\'s pawns; the Tarrasch Variation against 3. Nd2, where you strike with ...c5 and recapture with the queen; the Winawer Poisoned Pawn, where you let White\'s queen take kingside pawns in return for central play; and the Steinitz Variation, where you meet 4. e5 and f4 with ...c5 and ...f6. White replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['winawer-poisoned-pawn', 'Winawer, Poisoned Pawn: give up g7 and h7 for central play', 'Nine Black moves. After the Winawer exchange on c3, meet 7. Qg4 with ...Qc7, let the queen take on g7 and h7, put your rook on the g-file and win a pawn back with ...cxd4. Source: Winawer Variation, Poisoned Pawn Variation, Main Line.', 3, self::POISONED_SOURCE, self::POISONED_FACTS, [...$opening,
                ['Bb4', 'Move your bishop from f8 to b4.', 'Pin the knight on c3, which defends e4, against White\'s king. This is the Winawer Variation.'],
                ['e5', 'Move your pawn from e4 to e5.', 'White locks the centre and gains space.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Attack the base of White\'s pawn chain on d4 straight away.'],
                ['a3', 'Move your pawn from a2 to a3.', 'White asks your bishop to decide: retreat or exchange itself for the knight.'],
                ['Bxc3+', 'Move your bishop from b4 to c3, capturing the white knight with check.', 'Give up the bishop for the knight. In return, White will have doubled pawns on the c-file, which are harder to defend.'],
                ['bxc3', 'Move your pawn from b2 to c3, capturing the black bishop.', 'White recaptures. White now has two bishops, but also doubled c-pawns.'],
                ['Ne7', 'Move your knight from g8 to e7.', 'Develop the knight to e7, where the e5 pawn cannot attack it, unlike on f6.'],
                ['Qg4', 'Move your queen from d1 to g4.', 'White brings the queen out at once. It attacks your g7 pawn, which nothing defends now that your dark-squared bishop has been exchanged, and the e6 pawn. The queen also adds a defender to d4.'],
                ['Qc7', 'Move your queen from d8 to c7.', 'This is the Poisoned Pawn Variation. Instead of defending g7, counter-attack: your queen attacks the e5 pawn and defends c5, and once your c-pawn captures on d4 it will also bear down on c3. You let White take kingside pawns in return for play in the centre and open lines.'],
                ['Qxg7', 'Move your queen from g4 to g7, capturing the black pawn.', 'White takes the g7 pawn and attacks your rook on h8, which nothing defends, as well as the pawns on f7 and h7. White is now a pawn up.'],
                ['Rg8', 'Move your rook from h8 to g8.', 'Save the rook and gain time: from g8 it attacks the queen, which nothing defends. Your knight on e7 protects the rook, and the rook now stands on the g-file, where you no longer have a pawn.'],
                ['Qxh7', 'Move your queen from g7 to h7, capturing the black pawn.', 'The queen takes a second pawn and steps out of the rook\'s attack. White is two pawns up for now. The queen attacks your rook, which the knight defends, and the f7 pawn, which your king defends. Your rook now attacks the g2 pawn, defended by the bishop on f1.'],
                ['cxd4', 'Move your pawn from c5 to d4, capturing the white pawn.', 'Win a pawn back. Your new pawn on d4 attacks c3, which your queen also attacks along the c-file, and nothing defends the e5 pawn, which your queen attacks too. White is still a pawn up, but has to look after c3 and e5 at the same time.'],
                ['Ne2', 'Move your knight from g1 to e2.', 'White develops the knight to e2, where it defends c3 and attacks your pawn on d4, which is now attacked twice and not defended. The named Poisoned Pawn Main Line ends here: your next tasks are to deal with the threat to d4, develop the knight on b8 and the bishop on c8, and use the g-file and your pressure on c3 and e5 while White\'s king is still in the centre.'],
            ]],
            ['steinitz-brodsky-jones', 'Steinitz Variation: ...c5, ...Nc6 and ...f6 against the chain', 'Nine Black moves. Against 4. e5 and f4, attack the pawn chain with ...c5, win your pawn back with ...Bxc5, castle and strike at e5 again with ...f6. Source: Steinitz Variation, Brodsky-Jones Variation.', 4, self::STEINITZ_SOURCE, self::STEINITZ_FACTS, [...$opening,
                ['Nf6', 'Move your knight from g8 to f6.', 'Develop and attack e4 a second time. This is the Classical Variation.'],
                ['e5', 'Move your pawn from e4 to e5.', 'White advances at once instead of pinning your knight with Bg5. This is the Steinitz Variation: the pawn attacks your knight and gains space.'],
                ['Nfd7', 'Move your knight from f6 to d7.', 'Retreat the knight to d7. From there it attacks the e5 pawn and will support the ...c5 break.'],
                ['f4', 'Move your pawn from f2 to f4.', 'White supports e5 with a second pawn, forming a chain on d4, e5 and f4.'],
                ['c5', 'Move your pawn from c7 to c5.', 'Attack d4, the base of White\'s pawn chain. Only White\'s queen defends d4 at the moment, while your bishop on f8 and knight on d7 support c5.'],
                ['dxc5', 'Move your pawn from d4 to c5, capturing the black pawn.', 'White gives up the base of the chain and captures on c5. For the moment White is a pawn up, but your bishop and knight attack the c5 pawn and nothing defends it. Without the d4 pawn, only the f4 pawn supports e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Develop the knight instead of recapturing at once. It attacks e5, which is now attacked by both your knights and defended only by the f4 pawn, while the c5 pawn remains a target for your bishop and knight.'],
                ['a3', 'Move your pawn from a2 to a3.', 'White takes the b4 square away from your pieces, so your bishop cannot pin the knight on c3 from there, and prepares b2-b4, which would defend the c5 pawn.'],
                ['Bxc5', 'Move your bishop from f8 to c5, capturing the white pawn.', 'Win the pawn back before b4 can support it. Material is level. Your bishop now points down the diagonal at g1, the square White\'s king needs for kingside castling.'],
                ['Qg4', 'Move your queen from d1 to g4.', 'White brings the queen to g4, where it attacks your g7 pawn, which nothing defends, and the e6 pawn, which your f7 pawn defends. The queen also supports the f4 pawn.'],
                ['O-O', 'Castle: move your king from e8 to g8. The rook moves from h8 to f8 automatically.', 'Castle. Your king now defends g7, and your rook on f8 stands behind the f-pawn, ready to support ...f6.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'White develops the knight and adds a defender to e5. The pawn is now attacked twice, by your knights, and defended twice, by the f4 pawn and the knight.'],
                ['f6', 'Move your pawn from f7 to f6.', 'Attack e5 a third time, now with a pawn. If White takes on f6, your queen, rook or knight on d7 can recapture. Watch your e6 pawn: the f-pawn no longer defends it and the queen on g4 attacks it. The named Brodsky-Jones Variation ends here: your next tasks are to challenge the e5 pawn, develop the bishop on c8 and keep e6 and your king well protected.'],
            ]],
        ];

        if (3 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='french'")) {
            throw new \RuntimeException('French revision 4 must be published directly after revision 3.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='french' AND revision=3 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained French revision 3 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('French teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,4,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='french' AND revision=3", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,4,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='french' AND revision=3 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,4,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='french' AND revision=3 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('french',4,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('french',4,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved French edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=4 WHERE id='french'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous French sessions and progress.');
    }
}
