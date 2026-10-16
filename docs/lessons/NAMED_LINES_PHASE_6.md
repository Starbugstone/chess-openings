# Phase 6: four more courses and longer courses from complete named lines

The owner asked for many more lessons from the opening database ("go ham"). This phase was drafted in parallel with [phase 5](NAMED_LINES_PHASE_5.md) under the working name "phase 4". When PR #15 published nine of the drafted courses first, the drafts were rebased onto it:
- The phase-5 courses stay authoritative, and their lines keep their IDs and positions.
- Eight of those courses gain a **revision 2** that appends only genuinely different records.
- Drafts that repeated a phase-5 record, extended it by a ply or two, or transposed into it were dropped.
- The Petrov draft added only a line whose Black moves repeated the Maróczy line, so Petrov stays at revision 1.

As in earlier phases, every line is one **complete named record** from the pinned Lichess catalogue and gives the learner at least nine moves (17–26 plies). `LessonGraphV1::resolveImported()` checks every frozen UCI, SAN and canonical position key against the exact pinned record before lesson rows are queued. **No chess positions, moves or authored-provenance rows are added.** Hints follow the house template. Explanations, titles and introductions are original project prose, with French and Spanish entries in `frontend/src/i18n/lessons.json`.

## New courses

| Course | ID | Colour | Sort | Migration | Lines |
| --- | --- | --- | --- | --- | --- |
| Queen's Indian and Bogo-Indian | `queens-indian` | Black | 19 | `Version20261016030100` | 2 |
| Semi-Slav Defense | `semi-slav` | Black | 20 | `Version20261016030400` | 5 |
| Two Knights Defense | `two-knights` | Black | 21 | `Version20261016031300` | 4 |
| English Opening | `english` | White | 22 | `Version20261016031900` | 1 |

Each migration refuses to run if its course ID already exists. It inserts the course with an unpublished pointer, then revision 1, lines and steps, and publishes the pointer last in one transaction.

## New revisions

Retained lines are copied from the previous revision with `INSERT … SELECT` at unchanged positions. Each migration refuses to run unless the previous revision is still current and every retained line has its expected length. Earlier revisions, and the practice saved against them, are untouched.

| Course | Revision | Migration | New lines (appended) |
| --- | --- | --- | --- |
| Nimzo-Indian | 2 | `Version20261016030000` | `gligoric-isolated-pawn`, `st-petersburg-fianchetto` |
| Grünfeld | 2 | `Version20261016030200` | `modern-exchange-pawn-grab` |
| Benoni | 2 | `Version20261016030300` | `classical-argentine`, `fianchetto-hastings` |
| Tarrasch | 2 | `Version20261016030500` | `reti-dxc5`, `stoltz-b6` |
| Slav | 3 | `Version20261016030600` | `czech-morozevich` (plus two corrected lines, below) |
| Alekhine | 2 | `Version20261016030700` | `four-pawns-korchnoi` |
| Caro-Kann | 4 | `Version20261016030800` | `classical-seirawan` |
| French | 4 | `Version20261016030900` | `winawer-poisoned-pawn`, `steinitz-brodsky-jones` |
| Four Knights | 2 | `Version20261016031100` | `classical-bardeleben` |
| Evans Gambit | 2 | `Version20261016031200` | `paulsen-variation`, `compromised-defense` |
| Ruy Lopez | 2 | `Version20261016031400` | `zaitsev-hybrid`, `open-breslau`, `open-howell-ekstrom`, `berlin-wall` |
| Scotch | 2 | `Version20261016031500` | `steinitz-rosenthal`, `scotch-gambit-anderssen` |
| Sicilian | 2 | `Version20261016031600` | `richter-rauzer`, `sozin-sherbakov`, `scheveningen-classical`, `taimanov-modern`, `alapin-endgame` |
| Queen's Gambit | 3 | `Version20261016031700` | `exchange-reshevsky`, `lasker-bernstein`, `tartakower-defense`, `cambridge-springs` |
| Catalan | 2 | `Version20261016031800` | `closed-zagoryansky`, `closed-spassky-gambit` |
| King's Indian | 2 | `Version20261016032000` | `bayonet-sokolov`, `petrosian-keres`, `positional-defense`, `samisch-bronstein` (plus one corrected line, below) |

## Corrections to published notes

The independent fact-check found statements in already-published lines that the board does not support. Published revisions are immutable, so the new revision re-authors each affected line with the **same ID, path and position** and changes only the faulty sentence. Every other step stays word for word, so it keeps its translation.

| Line | Move | Published claim | Now |
| --- | --- | --- | --- |
| `slav/3/czech-main-line` | 5. a4 | no white pawn can ever attack b4 again | b4 is weakened: White's a-pawn has passed it and the c-pawn has gone, so for now no pawn can drive a piece from b4 |
| `slav/3/czech-main-line` | 10. g4 | the bishop must move and will stop supporting e4 | the bishop's only retreat is g6, where it still supports the knight on e4 |
| `slav/3/semi-slav-meran` | 5...Nbd7 | develop to d7 "rather than c6" (your own pawn already stands on c6) | d7 is the knight's natural square with the pawn on c6; it covers c5 and e5 |
| `kings-indian/2/classical-main-line` | 7...Nc6 | White has to settle the central tension | White usually settles it now (8. Be3 keeps it, and d4 is not under threat) |

## Source records

Pinned dataset: [`lichess-org/chess-openings`](https://github.com/lichess-org/chess-openings) at `a6189a30dc273ccb21fc2536a9a2fefd5592a67a` (CC0). Row numbers count the header as row 1. The exact PGNs are in each migration's `*_SOURCE` constants. The 48 records not already present are appended unchanged to `backend/tests/Fixtures/Lessons/source/*.tsv`. The re-authored lines' records were already there.

| Line | File, row | ECO | Exact name | Plies | Learner moves |
| --- | --- | --- | --- | --- | --- |
| `nimzo-indian/2/gligoric-isolated-pawn` | `e.tsv` 238 | E57 | Nimzo-Indian Defense: Normal Variation, Gligoric System, Bernstein Defense | 18 | 9 |
| `nimzo-indian/2/st-petersburg-fianchetto` | `e.tsv` 204 | E43 | Nimzo-Indian Defense: St. Petersburg Variation | 18 | 9 |
| `queens-indian/1/spassky-isolated-pawn` | `e.tsv` 105 | E14 | Queen's Indian Defense: Spassky System3 | 18 | 9 |
| `queens-indian/1/bogo-retreat` | `e.tsv` 62 | E11 | Bogo-Indian Defense: Retreat Variation | 20 | 10 |
| `grunfeld/2/modern-exchange-pawn-grab` | `d.tsv` 608 | D85 | Grünfeld Defense: Exchange Variation, Modern Exchange Variation, Pawn Grab Line | 20 | 10 |
| `benoni/2/classical-argentine` | `a.tsv` 748 | A75 | Benoni Defense: Classical Variation, Argentine Counterattack | 20 | 10 |
| `benoni/2/fianchetto-hastings` | `a.tsv` 722 | A64 | Benoni Defense: Fianchetto Variation, Hastings Defense, Main Line | 22 | 11 |
| `semi-slav/1/meran-wade-larsen` | `d.tsv` 460 | D47 | Semi-Slav Defense: Meran Variation, Wade Variation, Larsen Variation | 22 | 11 |
| `semi-slav/1/meran-chigorin-bd6` | `d.tsv` 452 | D46 | Semi-Slav Defense: Chigorin Defense | 21 | 10 |
| `semi-slav/1/meran-early-a6` | `d.tsv` 465 | D48 | Semi-Slav Defense: Meran Variation | 20 | 10 |
| `semi-slav/1/botvinnik-lilienthal` | `d.tsv` 427 | D44 | Semi-Slav Defense: Botvinnik Variation, Lilienthal Variation | 21 | 10 |
| `semi-slav/1/anti-meran-qc2` | `d.tsv` 441 | D45 | Semi-Slav Defense: Stoltz Variation, Center Variation, Mikhalchishin Line | 19 | 9 |
| `tarrasch/2/reti-dxc5` | `d.tsv` 356 | D34 | Tarrasch Defense: Classical Variation, Réti Variation | 19 | 9 |
| `tarrasch/2/stoltz-b6` | `d.tsv` 346 | D34 | Queen's Gambit Declined: Tarrasch Defense, Stoltz Variation | 20 | 10 |
| `slav/3/czech-main-line` | `d.tsv` 228 | D19 | Slav Defense: Czech Variation, Dutch Variation, Sämisch Variation | 19 | 9 |
| `slav/3/semi-slav-meran` | `d.tsv` 472 | D49 | Semi-Slav Defense: Meran Variation, Rellstab Attack | 29 | 14 |
| `slav/3/czech-morozevich` | `d.tsv` 219 | D17 | Slav Defense: Czech Variation, Carlsbad Variation, Morozevich Variation | 22 | 11 |
| `alekhine/2/four-pawns-korchnoi` | `b.tsv` 169 | B03 | Alekhine Defense: Four Pawns Attack, Korchnoi Variation | 20 | 10 |
| `caro-kann/4/classical-seirawan` | `b.tsv` 372 | B19 | Caro-Kann Defense: Classical Variation, Seirawan Variation | 24 | 12 |
| `french/4/winawer-poisoned-pawn` | `c.tsv` 197 | C18 | French Defense: Winawer Variation, Poisoned Pawn Variation, Main Line | 19 | 9 |
| `french/4/steinitz-brodsky-jones` | `c.tsv` 120 | C11 | French Defense: Steinitz Variation, Brodsky-Jones Variation | 18 | 9 |
| `four-knights/2/classical-bardeleben` | `c.tsv` 802 | C48 | Four Knights Game: Spanish Variation, Bardeleben Variation | 18 | 9 |
| `evans-gambit/2/paulsen-variation` | `c.tsv` 876 | C51 | Italian Game: Evans Gambit, Paulsen Variation | 20 | 10 |
| `evans-gambit/2/compromised-defense` | `c.tsv` 885 | C52 | Italian Game: Evans Gambit, Compromised Defense, Main Line | 21 | 11 |
| `two-knights/1/polerio-goring` | `c.tsv` 1014 | C59 | Italian Game: Two Knights Defense, Polerio Defense, Göring Variation | 20 | 10 |
| `two-knights/1/polerio-yankovich` | `c.tsv` 1012 | C58 | Italian Game: Two Knights Defense, Polerio Defense, Yankovich Variation | 19 | 9 |
| `two-knights/1/max-lange-berger` | `c.tsv` 979 | C56 | Italian Game: Two Knights Defense, Max Lange Attack, Berger Variation | 26 | 13 |
| `two-knights/1/max-lange-rubinstein` | `c.tsv` 982 | C56 | Italian Game: Two Knights Defense, Max Lange Attack, Rubinstein Variation | 22 | 11 |
| `ruy-lopez/2/zaitsev-hybrid` | `c.tsv` 1231 | C92 | Ruy Lopez: Closed, Smyslov-Breyer-Zaitsev Hybrid | 24 | 12 |
| `ruy-lopez/2/open-breslau` | `c.tsv` 1184 | C83 | Ruy Lopez: Open, Breslau Variation | 22 | 11 |
| `ruy-lopez/2/open-howell-ekstrom` | `c.tsv` 1176 | C81 | Ruy Lopez: Open, Howell Attack, Ekstrom Variation | 24 | 12 |
| `ruy-lopez/2/berlin-wall` | `c.tsv` 1082 | C67 | Ruy Lopez: Berlin Defense, Berlin Wall | 18 | 9 |
| `scotch/2/steinitz-rosenthal` | `c.tsv` 770 | C45 | Scotch Game: Rosenthal Variation | 18 | 9 |
| `scotch/2/scotch-gambit-anderssen` | `c.tsv` 964 | C56 | Italian Game: Scotch Gambit, Anderssen Attack, Main Line | 21 | 11 |
| `sicilian/2/richter-rauzer` | `b.tsv` 671 | B63 | Sicilian Defense: Richter-Rauzer Variation, Classical Variation, Kantscher Line | 20 | 10 |
| `sicilian/2/sozin-sherbakov` | `b.tsv` 746 | B89 | Sicilian Defense: Sozin Attack, Main Line, Sherbakov Variation | 20 | 10 |
| `sicilian/2/scheveningen-classical` | `b.tsv` 737 | B85 | Sicilian Defense: Scheveningen Variation, Classical Main Line | 20 | 10 |
| `sicilian/2/taimanov-modern` | `b.tsv` 610 | B44 | Sicilian Defense: Taimanov Variation, Modern Line | 20 | 10 |
| `sicilian/2/alapin-endgame` | `b.tsv` 449 | B22 | Sicilian Defense: Alapin Variation, Barmen Defense, Endgame Variation | 18 | 9 |
| `queens-gambit/3/exchange-reshevsky` | `d.tsv` 381 | D36 | Queen's Gambit Declined: Exchange Variation, Reshevsky Variation | 21 | 11 |
| `queens-gambit/3/lasker-bernstein` | `d.tsv` 518 | D57 | Queen's Gambit Declined: Lasker Defense, Bernstein Variation | 22 | 11 |
| `queens-gambit/3/tartakower-defense` | `d.tsv` 523 | D59 | Queen's Gambit Declined: Tartakower Defense | 22 | 11 |
| `queens-gambit/3/cambridge-springs` | `d.tsv` 492 | D52 | Queen's Gambit Declined: Cambridge Springs Defense, Argentine Variation | 17 | 9 |
| `catalan/2/closed-zagoryansky` | `e.tsv` 30 | E08 | Catalan Opening: Closed, Zagoryansky Variation | 17 | 9 |
| `catalan/2/closed-spassky-gambit` | `e.tsv` 29 | E08 | Catalan Opening: Closed, Spassky Gambit | 20 | 10 |
| `english/1/hedgehog-flexible-formation` | `a.tsv` 458 | A30 | English Opening: Symmetrical, Hedgehog, Flexible Formation | 20 | 10 |
| `kings-indian/2/classical-main-line` | `e.tsv` 365 | E98 | King's Indian Defense: Orthodox Variation, Classical System, Kozul Gambit | 27 | 13 |
| `kings-indian/2/bayonet-sokolov` | `e.tsv` 360 | E97 | King's Indian Defense: Orthodox Variation, Bayonet Attack, Sokolov's Line | 19 | 9 |
| `kings-indian/2/petrosian-keres` | `e.tsv` 348 | E93 | King's Indian Defense: Petrosian Variation, Keres Defense | 21 | 10 |
| `kings-indian/2/positional-defense` | `e.tsv` 357 | E96 | King's Indian Defense: Orthodox Variation, Positional Defense, Main Line | 18 | 9 |
| `kings-indian/2/samisch-bronstein` | `e.tsv` 335 | E87 | King's Indian Defense: Sämisch Variation, Orthodox Variation, Bronstein Variation | 24 | 12 |

## Editorial choices

- **Selection.** Records were chosen for breadth: a different White system or Black set-up per line, not near-duplicates.
  - The Sicilian adds the Richter-Rauzer, Sozin, Scheveningen and Taimanov, plus the Alapin as the common anti-Sicilian.
  - The Ruy Lopez adds Black's other main defences: the Zaitsev set-up, the Open Spanish and the Berlin.
  - The Queen's Gambit adds the Exchange, Lasker, Tartakower and Cambridge Springs.
  - Traps, mates and gambit sidelines were skipped, as were records ending in a forced sequence the learner never answers.
- **Sharp lines.**
  - **Sämisch Bronstein (King's Indian):** the only Sämisch record long enough. Black gives the queen for both bishops and two pawns. The notes state the material at each step and present it as a plan with a cost.
  - **Spassky Gambit (Catalan), Evans Gambit and Max Lange lines:** presented the same way.
- **Single-line course.** The English Opening has one record of 17+ plies (Symmetrical, Hedgehog, Flexible Formation), so it is a single-line course, as London is. Its introduction says so.
- **Catalogue name quirk.** The pinned name "Queen's Indian Defense: Spassky System3" contains a literal "3". It is quoted exactly, marked as the exact catalogue name.
- **Line order.**
  - In `queens-indian`, the Queen's Indian line comes before the deeper Bogo-Indian line because it is the course's title opening.
  - The Scotch Gambit Anderssen line reaches the Scotch Gambit through the record's own 3. Bc4 Nf6 4. d4 order, and its notes say so.
- **Shared prefixes reuse text.** Where a new line plays exactly the moves of a published line, its explanations reuse the published text when it stays true in the new line, which also reuses the translations. Otherwise the text says where the line takes its own path.
- **What the notes avoid.** As before, they contain no Opening Explorer statistics, engine evaluations or popularity claims.

## Process and verification

- **Authoring.** Ten authoring agents wrote the lines from exact catalogue facts. A board-analysis helper listed attacks, defenders, pins and material at every ply.
- **Fact-check.** Independent agents fact-checked every new explanation against that board output and corrected dozens of over-strong or wrong claims before translation.
- **Translation.** Separate agents translated 754 new strings into French and Spanish. A mechanical-hint generator reproduced all 234 existing hint translations byte for byte before filling new hints.
- **Verification.** Exact commands and results are recorded in [REVIEW_COORDINATION.md](../REVIEW_COORDINATION.md) (phase-4 sections) and the [curriculum review addendum](../CURRICULUM_REVIEW.md).
