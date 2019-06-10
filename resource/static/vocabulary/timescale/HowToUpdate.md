# How to update timescale

## How to rename a Concept

1. Clone the old Concept
2. Rename the original with the new name - this ensures all the orginal properties and links are preserved
3. Edit any properties that need to be synced with the new name
4. Add `dct:replaces` pointing to original named resource
4. rename the clone to the original name
5. Add `dct:isReplacedBy` to the old resource
6. Remove most relationships on the original so it doesn't get tangled up in the ontology, 
just leaving the replace links

