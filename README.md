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