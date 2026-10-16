<?php

declare(strict_types=1);

namespace AppMigrations\LessonData;

use AppMigrations\Support\LessonGraphV1;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

require_once __DIR__.'/../Support/LessonGraphV1.php';

/** Scotch Game revision 2: keeps the two revision 1 lines and appends two complete named pinned paths. */
final class Version20261016031500 extends AbstractMigration
{
    public const ROSENTHAL_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. d4 exd4 4. Nxd4 Qh4 5. Nb5 Bb4+ 6. Bd2 Qxe4+ 7. Be2 Kd8 8. O-O Bxd2 9. Nxd2 Qg6';
    public const ANDERSSEN_SOURCE = '1. e4 e5 2. Nf3 Nc6 3. Bc4 Nf6 4. d4 exd4 5. O-O Nxe4 6. Re1 d5 7. Bxd5 Qxd5 8. Nc3 Qa5 9. Nxe4 Be6 10. Bd2 Qd5 11. Bg5';
    /** @var array<string, array{int, int}> retained line => [previous ply count, new sort order] */
    private const KEPT_LINES = ['classical-gottschall' => [24, 0], 'scotch-gambit-max-lange' => [21, 1]];

    public const ROSENTHAL_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/3PP3/5N2/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f3d4', 'san' => 'Nxd4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'd8h4', 'san' => 'Qh4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/8/3NP3/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1b1kbnr/pppp1ppp/2n5/8/3NP2q/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'd4b5', 'san' => 'Nb5', 'fromKey' => 'r1b1kbnr/pppp1ppp/2n5/8/3NP2q/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1b1kbnr/pppp1ppp/2n5/1N6/4P2q/8/PPP2PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'f8b4', 'san' => 'Bb4+', 'fromKey' => 'r1b1kbnr/pppp1ppp/2n5/1N6/4P2q/8/PPP2PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2P2q/8/PPP2PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'c1d2', 'san' => 'Bd2', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2P2q/8/PPP2PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2P2q/8/PPPB1PPP/RN1QKB1R b KQkq -'],
        ['uci' => 'h4e4', 'san' => 'Qxe4+', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2P2q/8/PPPB1PPP/RN1QKB1R b KQkq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPB1PPP/RN1QKB1R w KQkq -'],
        ['uci' => 'f1e2', 'san' => 'Be2', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPB1PPP/RN1QKB1R w KQkq -', 'toKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1QK2R b KQkq -'],
        ['uci' => 'e8d8', 'san' => 'Kd8', 'fromKey' => 'r1b1k1nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1QK2R b KQkq -', 'toKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1QK2R w KQ -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1QK2R w KQ -', 'toKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1Q1RK1 b - -'],
        ['uci' => 'b4d2', 'san' => 'Bxd2', 'fromKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/1b2q3/8/PPPBBPPP/RN1Q1RK1 b - -', 'toKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/4q3/8/PPPbBPPP/RN1Q1RK1 w - -'],
        ['uci' => 'b1d2', 'san' => 'Nxd2', 'fromKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/4q3/8/PPPbBPPP/RN1Q1RK1 w - -', 'toKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/4q3/8/PPPNBPPP/R2Q1RK1 b - -'],
        ['uci' => 'e4g6', 'san' => 'Qg6', 'fromKey' => 'r1bk2nr/pppp1ppp/2n5/1N6/4q3/8/PPPNBPPP/R2Q1RK1 b - -', 'toKey' => 'r1bk2nr/pppp1ppp/2n3q1/1N6/8/8/PPPNBPPP/R2Q1RK1 w - -'],
    ];

    public const ANDERSSEN_FACTS = [
        ['uci' => 'e2e4', 'san' => 'e4', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -'],
        ['uci' => 'e7e5', 'san' => 'e5', 'fromKey' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -'],
        ['uci' => 'g1f3', 'san' => 'Nf3', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -', 'toKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -'],
        ['uci' => 'b8c6', 'san' => 'Nc6', 'fromKey' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -'],
        ['uci' => 'f1c4', 'san' => 'Bc4', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -', 'toKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -'],
        ['uci' => 'g8f6', 'san' => 'Nf6', 'fromKey' => 'r1bqkbnr/pppp1ppp/2n5/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'],
        ['uci' => 'd2d4', 'san' => 'd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -'],
        ['uci' => 'e5d4', 'san' => 'exd4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/4p3/2BPP3/5N2/PPP2PPP/RNBQK2R b KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -'],
        ['uci' => 'e1g1', 'san' => 'O-O', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQK2R w KQkq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -'],
        ['uci' => 'f6e4', 'san' => 'Nxe4', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n2n2/8/2BpP3/5N2/PPP2PPP/RNBQ1RK1 b kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n5/8/2Bpn3/5N2/PPP2PPP/RNBQ1RK1 w kq -'],
        ['uci' => 'f1e1', 'san' => 'Re1', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n5/8/2Bpn3/5N2/PPP2PPP/RNBQ1RK1 w kq -', 'toKey' => 'r1bqkb1r/pppp1ppp/2n5/8/2Bpn3/5N2/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'd7d5', 'san' => 'd5', 'fromKey' => 'r1bqkb1r/pppp1ppp/2n5/8/2Bpn3/5N2/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n5/3p4/2Bpn3/5N2/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'c4d5', 'san' => 'Bxd5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n5/3p4/2Bpn3/5N2/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r1bqkb1r/ppp2ppp/2n5/3B4/3pn3/5N2/PPP2PPP/RNBQR1K1 b kq -'],
        ['uci' => 'd8d5', 'san' => 'Qxd5', 'fromKey' => 'r1bqkb1r/ppp2ppp/2n5/3B4/3pn3/5N2/PPP2PPP/RNBQR1K1 b kq -', 'toKey' => 'r1b1kb1r/ppp2ppp/2n5/3q4/3pn3/5N2/PPP2PPP/RNBQR1K1 w kq -'],
        ['uci' => 'b1c3', 'san' => 'Nc3', 'fromKey' => 'r1b1kb1r/ppp2ppp/2n5/3q4/3pn3/5N2/PPP2PPP/RNBQR1K1 w kq -', 'toKey' => 'r1b1kb1r/ppp2ppp/2n5/3q4/3pn3/2N2N2/PPP2PPP/R1BQR1K1 b kq -'],
        ['uci' => 'd5a5', 'san' => 'Qa5', 'fromKey' => 'r1b1kb1r/ppp2ppp/2n5/3q4/3pn3/2N2N2/PPP2PPP/R1BQR1K1 b kq -', 'toKey' => 'r1b1kb1r/ppp2ppp/2n5/q7/3pn3/2N2N2/PPP2PPP/R1BQR1K1 w kq -'],
        ['uci' => 'c3e4', 'san' => 'Nxe4', 'fromKey' => 'r1b1kb1r/ppp2ppp/2n5/q7/3pn3/2N2N2/PPP2PPP/R1BQR1K1 w kq -', 'toKey' => 'r1b1kb1r/ppp2ppp/2n5/q7/3pN3/5N2/PPP2PPP/R1BQR1K1 b kq -'],
        ['uci' => 'c8e6', 'san' => 'Be6', 'fromKey' => 'r1b1kb1r/ppp2ppp/2n5/q7/3pN3/5N2/PPP2PPP/R1BQR1K1 b kq -', 'toKey' => 'r3kb1r/ppp2ppp/2n1b3/q7/3pN3/5N2/PPP2PPP/R1BQR1K1 w kq -'],
        ['uci' => 'c1d2', 'san' => 'Bd2', 'fromKey' => 'r3kb1r/ppp2ppp/2n1b3/q7/3pN3/5N2/PPP2PPP/R1BQR1K1 w kq -', 'toKey' => 'r3kb1r/ppp2ppp/2n1b3/q7/3pN3/5N2/PPPB1PPP/R2QR1K1 b kq -'],
        ['uci' => 'a5d5', 'san' => 'Qd5', 'fromKey' => 'r3kb1r/ppp2ppp/2n1b3/q7/3pN3/5N2/PPPB1PPP/R2QR1K1 b kq -', 'toKey' => 'r3kb1r/ppp2ppp/2n1b3/3q4/3pN3/5N2/PPPB1PPP/R2QR1K1 w kq -'],
        ['uci' => 'd2g5', 'san' => 'Bg5', 'fromKey' => 'r3kb1r/ppp2ppp/2n1b3/3q4/3pN3/5N2/PPPB1PPP/R2QR1K1 w kq -', 'toKey' => 'r3kb1r/ppp2ppp/2n1b3/3q2B1/3pN3/5N2/PPP2PPP/R2QR1K1 b kq -'],
    ];

    public function getDescription(): string
    {
        return 'Publish Scotch Game revision 2: keep two lines, append the Rosenthal Variation and the Scotch Gambit Anderssen Attack (steinitz-rosenthal, scotch-gambit-anderssen)';
    }

    public function isTransactional(): bool
    {
        return true;
    }

    public function up(Schema $schema): void
    {
        $introduction = 'Play White in the Scotch Game: after 1. e4 e5 2. Nf3 Nc6 you open the centre with d4 and develop quickly. The Gottschall Variation teaches the classical recapture 4. Nxd4: you defend the knight against ...Bc5 and ...Qf6, then take two pawns while Black develops against your uncastled king. The Max Lange Attack teaches the Scotch Gambit 4. Bc4: you leave the d4 pawn, castle, push e5 and use the open e-file and your knights. The Rosenthal Variation meets 4...Qh4 with Nb5: you give up e4 and develop while Black\'s king stays in the centre. The Anderssen Attack reaches the Scotch Gambit through 3. Bc4 Nf6 4. d4: you castle, pin the knight on e4 with Re1 and win back the piece. Black replies automatically along the selected line; real opponents can choose other continuations.';
        /** @var list<array{string, string, string, int, string, list<array{uci: string, san: string, fromKey: string, toKey: string}>, list<array{string, string, string}>}> $lines */
        $lines = [
            ['steinitz-rosenthal', 'Rosenthal: give up e4 against ...Qh4 and develop fast', 'Nine White moves. Meet the queen sortie ...Qh4 by attacking c7 with Nb5, give up the e4 pawn, castle and develop with gain of time while Black\'s king stays in the centre. Source: Rosenthal Variation.', 2, self::ROSENTHAL_SOURCE, self::ROSENTHAL_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open diagonals for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop with a threat: the knight attacks the pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 by developing a knight.'],
                ['d4', 'Move your pawn from d2 to d4.', 'This is the Scotch Game. Strike in the centre at once: the pawn and your knight both attack e5, and only the knight on c6 defends it.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Black takes on d4 rather than defend e5 a second time. You can win this pawn back, or leave it there for quicker development.'],
                ['Nxd4', 'Move your knight from f3 to d4, capturing the black pawn.', 'This is where this line chooses its own path: win the pawn back at once with the knight. It stands in the centre, attacked by the knight on c6 and defended by your queen.'],
                ['Qh4', 'Move your queen from d8 to h4.', 'This is the Steinitz Variation, and here this line leaves the Gottschall line. Black\'s queen attacks your pawn on e4, which nothing defends, as well as the pawns on f2 and h2. The pawn on f2 is pinned to your king, so it cannot move.'],
                ['Nb5', 'Move your knight from d4 to b5.', 'This is the Horwitz Attack. Do not defend e4: counterattack instead. Your knight attacks the pawn on c7, which nothing defends, and threatens to take it with check, forking the king on e8 and the rook on a8.'],
                ['Bb4+', 'Move your bishop from f8 to b4, giving check.', 'Black develops the bishop with check and gains time before dealing with your threat on c7.'],
                ['Bd2', 'Move your bishop from c1 to d2.', 'Block the check with the bishop. It attacks Black\'s bishop on b4, which the knight on c6 defends. Your bishop is pinned to your king for now.'],
                ['Qxe4+', 'Move your queen from h4 to e4, capturing the white pawn with check.', 'Black takes the pawn on e4 with check. Black is a pawn up, and the queen also attacks your pawns on c2 and g2.'],
                ['Be2', 'Move your bishop from f1 to e2.', 'Block the check by developing your other bishop. Both of your bishops are pinned to your king for the moment, and the pawn on g2 is undefended, but Black still has to deal with your threat on c7.'],
                ['Kd8', 'Move your king from e8 to d8.', 'Black defends c7 with the king. The king has moved, so Black can no longer castle.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle. Your king reaches safety, your bishops are no longer pinned and the king now defends g2. You are a pawn down, but you are ahead in development and Black\'s king is stuck in the centre.'],
                ['Bxd2', 'Move your bishop from b4 to d2, capturing the white bishop.', 'Black takes your bishop on d2. The black bishop there is attacked by your knight on b1 and your queen, and nothing defends it.'],
                ['Nxd2', 'Move your knight from b1 to d2, capturing the black bishop.', 'Recapture with the knight, which develops it and attacks Black\'s queen on e4 at the same time. Black is still a pawn up.'],
                ['Qg6', 'Move your queen from e4 to g6.', 'Black\'s queen retreats to g6, where it attacks your pawns on c2 and g2 and pins the g2 pawn to your king. The named Rosenthal Variation ends here: you are a pawn down but ahead in development. Your next tasks are to bring your rooks and queen into play quickly, keep your knight on b5 active against c7 and a7, and attack Black\'s king while it is still in the centre.'],
            ]],
            ['scotch-gambit-anderssen', 'Anderssen Attack: pin the knight with Re1 and regain the piece', 'Eleven White moves. Reach the Scotch Gambit through 3. Bc4 and 4. d4, leave the d4 pawn to Black, castle and pin the knight on e4 with Re1, then win the piece back and develop. Source: Scotch Gambit, Anderssen Attack, Main Line.', 3, self::ANDERSSEN_SOURCE, self::ANDERSSEN_FACTS, [
                ['e4', 'Move your pawn from e2 to e4.', 'Take space in the centre and open diagonals for your queen and the bishop on f1.'],
                ['e5', 'Move your pawn from e7 to e5.', 'Black claims an equal share of the centre and stops your e-pawn from advancing.'],
                ['Nf3', 'Move your knight from g1 to f3.', 'Develop with a threat: the knight attacks the pawn on e5.'],
                ['Nc6', 'Move your knight from b8 to c6.', 'Black defends e5 by developing a knight.'],
                ['Bc4', 'Move your bishop from f1 to c4.', 'This is where this line chooses its own path: develop the bishop before playing d4, as in the Italian Game. It aims at f7, the square next to Black\'s king that only the king defends.'],
                ['Nf6', 'Move your knight from g8 to f6.', 'Black develops the knight and attacks your pawn on e4, which nothing defends. This is the Two Knights Defense.'],
                ['d4', 'Move your pawn from d2 to d4.', 'Strike in the centre instead of defending e4. Your pawn attacks e5. If Black takes on d4, you do not hurry to win the pawn back: you use the time to develop and open lines.'],
                ['exd4', 'Move your pawn from e5 to d4, capturing the white pawn.', 'Black takes the pawn and is now a pawn up. The knight on f6 still attacks your e4 pawn. This is the Scotch Gambit position of the Max Lange line after 4...Nf6, reached by another move order: there you played d4 before Bc4.'],
                ['O-O', 'Castle: move your king from e1 to g1. The rook moves from h1 to f1 automatically.', 'Castle and leave e4 as it is. Your king is safe, and the rook on f1 can quickly reach the e-file if it opens.'],
                ['Nxe4', 'Move your knight from f6 to e4, capturing the white pawn.', 'Here this line leaves the Max Lange line, where Black plays ...Bc5. Black takes a second pawn instead, and the knight now stands on the e-file in front of Black\'s own king on e8.'],
                ['Re1', 'Move your rook from f1 to e1.', 'Attack the knight with the rook. The knight is pinned: it cannot move without exposing Black\'s king on e8 to your rook, and nothing defends it.'],
                ['d5', 'Move your pawn from d7 to d5.', 'Black defends the knight with the d-pawn and attacks your bishop on c4 at the same time.'],
                ['Bxd5', 'Move your bishop from c4 to d5, capturing the black pawn.', 'Take the pawn on d5 with the bishop. This removes the knight\'s only defender, and the bishop now attacks the knights on e4 and c6 and the pawn on f7. Nothing defends your bishop, and Black\'s queen can take it.'],
                ['Qxd5', 'Move your queen from d8 to d5, capturing the white bishop.', 'Black takes the bishop with the queen, which now defends the pinned knight. For the moment Black is a bishop and a pawn ahead.'],
                ['Nc3', 'Move your knight from b1 to c3.', 'Develop the knight with a double attack: it hits Black\'s queen on d5 and the knight on e4. The queen must move, and the pinned knight cannot escape.'],
                ['Qa5', 'Move your queen from d5 to a5.', 'Black\'s queen moves to a5, where it attacks your knight on c3 and the pawn on a2. Nothing defends the knight on e4 any more.'],
                ['Nxe4', 'Move your knight from c3 to e4, capturing the black knight.', 'Take the pinned knight. You have won back the piece, so Black is only a pawn up. Black\'s queen now attacks your rook on e1, which your knight on f3 and your queen defend. Your knight on e4 stands in front of the rook: if it moves away, the rook gives check.'],
                ['Be6', 'Move your bishop from c8 to e6.', 'Black develops the bishop and blocks the e-file between your rook and the king, so a knight move no longer gives check. The bishop also attacks your pawn on a2 together with the queen.'],
                ['Bd2', 'Move your bishop from c1 to d2.', 'Develop the bishop with gain of time: it attacks Black\'s queen on a5 and blocks the queen\'s line to your rook on e1.'],
                ['Qd5', 'Move your queen from a5 to d5.', 'Black\'s queen returns to the centre. It defends the pawn on d4 and the bishop on e6, and attacks your knight on e4, which your rook defends, and the pawn on a2.'],
                ['Bg5', 'Move your bishop from d2 to g5.', 'Bring the bishop to g5, where both knights defend it. It controls d8, so Black cannot castle queenside, and the bishop on f8 still blocks kingside castling. The named Anderssen Attack Main Line ends here: Black is a pawn up, but the king is still in the centre. Your next tasks are to bring your queen and the other rook into play and to win back the pawn on d4.'],
            ]],
        ];

        if (1 !== (int) $this->connection->fetchOne("SELECT current_revision FROM lesson WHERE id='scotch'")) {
            throw new \RuntimeException('Scotch Game revision 2 must be published directly after revision 1.');
        }
        foreach (self::KEPT_LINES as $lineId => [$plies]) {
            if ($plies !== (int) $this->connection->fetchOne("SELECT ply_count FROM lesson_line WHERE lesson_id='scotch' AND revision=1 AND id=?", [$lineId])) {
                throw new \RuntimeException('A retained Scotch Game revision 1 line is missing or changed: '.$lineId);
            }
        }
        $graph = new LessonGraphV1($this->connection);
        $resolved = [];
        foreach ($lines as [$lineId, , , , $source, $facts, $steps]) {
            if (array_column($steps, 0) !== array_column($facts, 'san')) {
                throw new \LogicException('Scotch Game teaching text and frozen path disagree: '.$lineId);
            }
            $resolved[$lineId] = $graph->resolveImported($facts, $source);
        }

        $this->addSql("INSERT INTO lesson_revision (lesson_id,revision,title,introduction,start_position_id,published_at,recommended_color) SELECT lesson_id,2,title,?,start_position_id,UTC_TIMESTAMP(),recommended_color FROM lesson_revision WHERE lesson_id='scotch' AND revision=1", [$introduction]);
        foreach (self::KEPT_LINES as $lineId => [, $order]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) SELECT lesson_id,2,id,title,description,?,ply_count FROM lesson_line WHERE lesson_id='scotch' AND revision=1 AND id=?", [$order, $lineId]);
            $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) SELECT lesson_id,2,line_id,ply,chess_move_id,hint,explanation FROM lesson_line_step WHERE lesson_id='scotch' AND revision=1 AND line_id=?", [$lineId]);
        }
        foreach ($lines as [$lineId, $title, $description, $order, , , $steps]) {
            $this->addSql("INSERT INTO lesson_line (lesson_id,revision,id,title,description,sort_order,ply_count) VALUES ('scotch',2,?,?,?,?,?)", [$lineId, $title, $description, $order, count($steps)]);
            foreach ($steps as $ply => [, $hint, $explanation]) {
                $this->addSql("INSERT INTO lesson_line_step (lesson_id,revision,line_id,ply,chess_move_id,hint,explanation) VALUES ('scotch',2,?,?,?,?,?)", [$lineId, $ply + 1, $resolved[$lineId][$ply] ?? throw new \LogicException('Missing resolved Scotch Game edge.'), $hint, $explanation]);
            }
        }
        $this->addSql("UPDATE lesson SET current_revision=2 WHERE id='scotch'");
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Publish a new revision; preserve previous Scotch Game sessions and progress.');
    }
}
