#!/usr/bin/env python3
"""Debug: mostra XML dels paràgrafs classificats com h1."""
import sys
from docx import Document
from lxml import etree
sys.path.insert(0, '.')
from corrector import classify_para, get_heading_level

doc = Document(sys.argv[1])
found = 0
for i, para in enumerate(doc.paragraphs):
    ptype = classify_para(para)
    if ptype == 'h1':
        found += 1
        print(f"\n=== h1 #{found} — paràgraf {i} ===")
        print(f"  text: {repr(para.text[:80])}")
        print(f"  style: {para.style.name}")
        print(f"  runs ({len(para.runs)}):")
        for j, r in enumerate(para.runs):
            print(f"    run[{j}]: {repr(r.text[:40])} | font={r.font.name} bold={r.bold}")
        print("  XML del paràgraf:")
        print(etree.tostring(para._p, pretty_print=True).decode())
        if found >= 3:
            break
if not found:
    print("Cap paràgraf classificat com h1 trobat.")
