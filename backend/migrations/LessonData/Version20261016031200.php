<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Evans Gambit revision 2: keeps the two revision 1 lines and appends two complete named pinned paths. */
final class Version20261016031200 extends AbstractMigration
{
    public const PAULSEN_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Bc5 4. b4 Bxb4 5. c3 Bc5 6. d4 exd4 7. O-O d6 8. cxd4 Bb6 9. d5 Na5 10. Bb2 Ne7';
    public const COMPROMISED_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Bc5 4. b4 Bxb4 5. c3 Ba5 6. d4 exd4 7. O-O dxc3 8. Qb3 Qf6 9. e5 Qg6 10. Nxc3 Nge7 11. Ba3';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['accepted-steinitz' => [21, 0], 'accepted-levenfish' => [21, 1]];

    public const PAULSEN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b2b4', 'san' => 'b4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/1PB1P3/5N2/P1PP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c5b4', 'san' => 'Bxb4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/1PB1P3/5N2/P1PP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/5N2/P1PP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'c2c3', 'san' => 'c3', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/5N2/P1PP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/2P2N2/P2P1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'b4c5', 'san' => 'Bc5', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/2P2N2/P2P1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/2P2N2/P2P1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/2P2N2/P2P1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2BPP3/2P2N2/P4PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2BPP3/2P2N2/P4PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b5/2BpP3/2P2N2/P4PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b5/2BpP3/2P2N2/P4PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b5/2BpP3/2P2N2/P4PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd7d6', 'san' => 'd6', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b5/2BpP3/2P2N2/P4PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/2np4/2b5/2BpP3/2P2N2/P4PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'c3d4', 'san' => 'cxd4', 'fromKey' => 'r1bqk1nr/ppp2ppp/2np4/2b5/2BpP3/2P2N2/P4PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/2np4/2b5/2BPP3/5N2/P4PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'c5b6', 'san' => 'Bb6', 'fromKey' => 'r1bqk1nr/ppp2ppp/2np4/2b5/2BPP3/5N2/P4PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/1bnp4/8/2BPP3/5N2/P4PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd4d5', 'san' => 'd5', 'fromKey' => 'r1bqk1nr/ppp2ppp/1bnp4/8/2BPP3/5N2/P4PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/1bnp4/3P4/2B1P3/5N2/P4PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'c6a5', 'san' => 'Na5', 'fromKey' => 'r1bqk1nr/ppp2ppp/1bnp4/3P4/2B1P3/5N2/P4PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/1b1p4/n2P4/2B1P3/5N2/P4PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'c1b2', 'san' => 'Bb2', 'fromKey' => 'r1bqk1nr/ppp2ppp/1b1p4/n2P4/2B1P3/5N2/P4PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk1nr/ppp2ppp/1b1p4/n2P4/2B1P3/5N2/PB3PPP/RN1Q1RK1 b kq -'],
        ['uci' => 'g8e7', 'san' => 'Ne7', 'fromKey' => 'r1bqk1nr/ppp2ppp/1b1p4/n2P4/2B1P3/5N2/PB3PPP/RN1Q1RK1 b kq -', 'toKey' => 'r1bqk2r/ppp1nppp/1b1p4/n2P4/2B1P3/5N2/PB3PPP/RN1Q1RK1 w kq -'],
    ];

    public const COMPROMISED_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'f8c5', 'san' => 'Bc5', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'b2b4', 'san' => 'b4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/1PB1P3/5N2/P1PP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'c5b4', 'san' => 'Bxb4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/2b1p3/1PB1P3/5N2/P1PP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/5N2/P1PP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'c2c3', 'san' => 'c3', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/5N2/P1PP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/2P2N2/P2P1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'b4a5', 'san' => 'Ba5', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/4p3/1bB1P3/2P2N2/P2P1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b3p3/2B1P3/2P2N2/P2P1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b3p3/2B1P3/2P2N2/P2P1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b3p3/2BPP3/2P2N2/P4PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b3p3/2BPP3/2P2N2/P4PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2BpP3/2P2N2/P4PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2BpP3/2P2N2/P4PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2BpP3/2P2N2/P4PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'd4c3', 'san' => 'dxc3', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2BpP3/2P2N2/P4PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2B1P3/2p2N2/P4PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'd1b3', 'san' => 'Qb3', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2B1P3/2p2N2/P4PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2B1P3/1Qp2N2/P4PPP/RNB2RK1 b kq -'],
        ['uci' => 'd8f6', 'san' => 'Qf6', 'fromKey' => 'r1bqk1nr/pppp1ppp/2n5/b7/2B1P3/1Qp2N2/P4PPP/RNB2RK1 b kq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n2q2/b7/2B1P3/1Qp2N2/P4PPP/RNB2RK1 w kq -'],
        ['uci' => 'e4e5', 'san' => 'e5', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n2q2/b7/2B1P3/1Qp2N2/P4PPP/RNB2RK1 w kq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n2q2/b3P3/2B5/1Qp2N2/P4PPP/RNB2RK1 b kq -'],
        ['uci' => 'f6g6', 'san' => 'Qg6', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n2q2/b3P3/2B5/1Qp2N2/P4PPP/RNB2RK1 b kq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n3q1/b3P3/2B5/1Qp2N2/P4PPP/RNB2RK1 w kq -'],
        ['uci' => 'b1c3', 'san' => 'Nxc3', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n3q1/b3P3/2B5/1Qp2N2/P4PPP/RNB2RK1 w kq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n3q1/b3P3/2B5/1QN2N2/P4PPP/R1B2RK1 b kq -'],
        ['uci' => 'g8e7', 'san' => 'Nge7', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n3q1/b3P3/2B5/1QN2N2/P4PPP/R1B2RK1 b kq -', 'toKey' => 'r1b1k2r/ppppnppp/2n3q1/b3P3/2B5/1QN2N2/P4PPP/R1B2RK1 w kq -'],
        ['uci' => 'c1a3', 'san' => 'Ba3', 'fromKey' => 'r1b1k2r/ppppnppp/2n3q1/b3P3/2B5/1QN2N2/P4PPP/R1B2RK1 w kq -', 'toKey' => 'r1b1k2r/ppppnppp/2n3q1/b3P3/2B5/BQN2N2/P4PPP/R4RK1 b kq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Evans Gambit revision 2: keep two lines, append the Paulsen Variation and the Compromised Defense (paulsen-variation, compromised-defense)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play White with 1. e4 e5 2. Nf3 Nc6 3. Bc4 Bc5 4. b4, the Evans Gambit. You offer a pawn: if the bishop takes it, c3 and d4 follow with gain of time against the bishop, and your pieces quickly aim at f7 and Black\'s king. All four lines start with 4...Bxb4 5. c3. The Steinitz Variation builds the d4 and e4 centre after 5...Bc5 and develops while Black\'s knight chases your bishop. The Levenfish Variation answers 5...Ba5 with your queen on b3 and bishop on a3 against f7 and Black\'s uncastled king. After 5...Bc5, the Paulsen Variation gains space with d5 and puts a bishop on b2. In the Compromised Defense, Black takes three pawns after 5...Ba5 and you play for development and attack. Black replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['paulsen-variation', 'Paulsen: d5 and the bishop on b2', 'Ten White moves. Give up the b-pawn and build a d4 and e4 centre as in the Steinitz line, then gain space with d5 and put your dark-squared bishop on the long diagonal towards g7. Source: Evans Gambit, Paulsen Variation.', 2, self::PAULSEN_SOURCE, self::PAULSEN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open diagonals for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop with a threat: the knight attacks the pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 by developing a knight.'],
                ['Bc4', 'Move your bishop from f1 to c4.', 'The bishop aims at f7, the square next to Black\'s king that only the king defends. Moving it also clears the way for castling.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Black mirrors your idea: this bishop aims at f2, the sensitive square next to your king.'],
                ['b4', 'Move your pawn from b2 to b4.', 'This is the Evans Gambit. Offer your b-pawn: it attacks the bishop on c5. If Black takes it with the bishop, c3 attacks the bishop again and prepares d4, so you build your centre with gain of time.'],
                ['Bxb4', 'Move your bishop from c5 to b4, capturing the white pawn.', 'Black accepts the gambit and takes the pawn with the bishop. You are now a pawn down.'],
                ['c3', 'Move your pawn from c2 to c3.', 'Attack the bishop again. The pawn on c3 also prepares d4, so you gain time for your centre while the bishop moves.'],
                ['Bc5', 'Move your bishop from b4 to c5.', 'Black retreats the bishop to c5, back on the diagonal towards f2. This line follows the Steinitz line until your ninth move.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Strike in the centre. The pawn attacks the bishop on c5 and the pawn on e5, so Black must react at once.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Black takes on d4. For the moment Black is two pawns up, and the pawn on d4 attacks your c3 pawn.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle before recapturing. Your king is safe and the rook comes to f1. Black could take another pawn with ...dxc3; this line follows ...d6.'],
                ['d6', 'Move your pawn from d7 to d6.', 'Black protects the bishop on c5 with a pawn and opens the diagonal of the bishop on c8.'],
                ['cxd4', 'Move your pawn from c3 to d4, capturing the black pawn.', 'Recapture with the c-pawn. You have pawns on d4 and e4, the bishop on c5 is attacked again, and Black is still a pawn up.'],
                ['Bb6', 'Move your bishop from c5 to b6.', 'Black retreats the bishop to b6. It stays on the same diagonal and keeps pressure on your d4 pawn.'],
                ['d5', 'Move your pawn from d4 to d5.', 'Here this line leaves the Steinitz line, which develops with Nc3. Advance instead: the pawn attacks the knight on c6 and gains space. The cost: with no pawn on d4, Black\'s bishop on b6 now aims straight at f2 and pins that pawn to your king.'],
                ['Na5', 'Move your knight from c6 to a5.', 'Black moves the knight away and attacks your bishop on c4, which nothing defends.'],
                ['Bb2', 'Move your bishop from c1 to b2.', 'This is the Ulvestad Variation. Instead of moving the bishop on c4, develop the other bishop onto the long diagonal, where it attacks the pawn on g7, which nothing defends, with the rook on h8 behind it. If Black takes your bishop with ...Nxc4, the check Qa4+ wins the knight back.'],
                ['Ne7', 'Move your knight from g8 to e7.', 'Black develops the knight to e7, where it attacks your d5 pawn and clears the way for kingside castling. Your bishop on b2 still attacks g7. The named Paulsen Variation ends here: your next tasks are to decide what to do with your bishop on c4, which the knight on a5 still attacks, keep d5 protected, develop the knight on b1 and use your space and the long diagonal against Black\'s kingside.'],
            ]],
            ['compromised-defense', 'Compromised Defense: three pawns for development', 'Eleven White moves. Black takes three pawns; you castle, keep Black busy with Qb3 and e5, win one pawn back with Nc3 and develop your last bishop to a3. Source: Evans Gambit, Compromised Defense, Main Line.', 3, self::COMPROMISED_SOURCE, self::COMPROMISED_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open diagonals for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop with a threat: the knight attacks the pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 by developing a knight.'],
                ['Bc4', 'Move your bishop from f1 to c4.', 'The bishop aims at f7, the square next to Black\'s king that only the king defends. Moving it also clears the way for castling.'],
                ['Bc5', 'Move your bishop from f8 to c5.', 'Black mirrors your idea: this bishop aims at f2, the sensitive square next to your king.'],
                ['b4', 'Move your pawn from b2 to b4.', 'This is the Evans Gambit. Offer your b-pawn: it attacks the bishop on c5. If Black takes it with the bishop, c3 attacks the bishop again and prepares d4, so you build your centre with gain of time.'],
                ['Bxb4', 'Move your bishop from c5 to b4, capturing the white pawn.', 'Black accepts the gambit and takes the pawn with the bishop. You are now a pawn down.'],
                ['c3', 'Move your pawn from c2 to c3.', 'Attack the bishop again. The pawn on c3 also prepares d4, so you gain time for your centre while the bishop moves.'],
                ['Ba5', 'Move your bishop from b4 to a5.', 'Black retreats the bishop to a5, where it presses on your c3 pawn. Once your d-pawn moves, the bishop will pin that pawn to your king. This line follows the Levenfish line until Black\'s sixth move.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Strike in the centre: the pawn attacks e5. Your c3 pawn is now pinned to your king by the bishop on a5, so it could not recapture if Black took on d4.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Here this line leaves the Levenfish line, where Black supports e5 with ...d6. Black captures on d4, and you are now two pawns down. Your c3 pawn is pinned, so it cannot take back.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle and offer a third pawn: the pawn on d4 can take on c3. With your king off e1, the bishop on a5 no longer pins your c3 pawn.'],
                ['dxc3', 'Move your pawn from d4 to c3, capturing the white pawn.', 'This is the Compromised Defense. Black takes a third pawn, but has developed only a knight and a bishop, and the king is still in the centre. Your task is to use that time.'],
                ['Qb3', 'Move your queen from d1 to b3.', 'Bring your queen to b3. It lines up behind your bishop on the diagonal towards f7 and attacks the pawns on b7 and c3.'],
                ['Qf6', 'Move your queen from d8 to f6.', 'Black defends f7 and the pawn on c3 with the queen, which also attacks your knight on f3.'],
                ['e5', 'Move your pawn from e4 to e5.', 'Attack the queen with a pawn. Your knight on f3 defends the pawn on e5, and Black has to answer the threat to the queen before developing another piece.'],
                ['Qg6', 'Move your queen from f6 to g6.', 'Black moves the queen to g6, where it pins your g2 pawn against your king and attacks your knight on b1.'],
                ['Nxc3', 'Move your knight from b1 to c3, capturing the black pawn.', 'Win one pawn back while developing your knight. You are now two pawns down. The knight is attacked by the bishop on a5 and defended by your queen.'],
                ['Nge7', 'Move your knight from g8 to e7.', 'Your pawn on e5 controls f6, so Black\'s knight develops to e7 instead. There it guards the queen on g6 and the knight on c6 and clears the way for castling.'],
                ['Ba3', 'Move your bishop from c1 to a3.', 'Develop your last minor piece onto the a3-f8 diagonal. It attacks the knight on e7, and if that knight moves, the bishop covers f8. You are two pawns down, but all your minor pieces are in play and your king is castled, while Black\'s king is still in the centre. The named Compromised Defense Main Line ends here: your next tasks are to bring your rooks to the centre files and open lines before Black\'s king reaches safety.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='evans-gambit'")) {
            throw new \RuntimeException('Evans Gambit revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='evans-gambit' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Evans Gambit revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Evans Gambit teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='evans-gambit' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='evans-gambit' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='evans-gambit' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('evans-gambit',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('evans-gambit',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Evans Gambit edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='evans-gambit'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Evans Gambit sessions and progress.');
    }
}
