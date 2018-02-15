# Genome Sequence

This task is based on a technique used for genome shotgun sequencing in the Human Genome
Project. Current sequencing technology can only sequence chunks of 500 or so base pairs, yet the
human genome is billions of pairs long. Thus, the DNA to be sequenced is cloned several times and
the clones are randomly cut into sequenceable-sized chunks. The reassembly step aligns those
chunks to reconstruct the original strand. Nova had a fascinating show on Cracking the Code of Life.
Their online animation of a genome sequencer explains the process of gene sequencing.

## Objective

Write a program that reads a file of text fragments and attempts to reconstruct the original document
out of the fragments. The fragments were created by duplicating the original document many times
over and chopping each copy into pieces. The fragments overlap one another and your program will
search for overlaps and align the fragments to reassemble them into their original order.

### Fragment Source Text
* a l l i s w e l l
* e l l t h a t e n
* h a t e n d
* t e n d s w e l l

### Expected Source Result Text
* a l l i s w e l l t h a t e n d s w e l l

## How To Verify Correctness

#### Pull In Dependencies

Run From CLI ([Composer](https://getcomposer.org/download/) Needed)

```
composer install
``` 

#### Unit Tests

Run From CLI

```
vendor/bin/phpunit
``` 

##### PHPUnit Output

```
Logans-MacBook-Pro:aderantChallenge LoganConnor$ vendor/bin/phpunit
PHPUnit 6.5.6 by Sebastian Bergmann and contributors.

.........                                                           9 / 9 (100%)

Time: 31 ms, Memory: 4.00MB

OK (9 tests, 13 assertions)
```