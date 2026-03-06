# Pla d'automatització de correcció de format — InDret

## Visió general

L'objectiu és construir un sistema que rebi articles en format `.docx` o `.doc`, els analitzi i produeixi un document corregit segons el Libro de estilo d'InDret, juntament amb un informe de canvis. L'editor final revisa i aprova el resultat.

---

## Arquitectura proposada

```
[Document entrada .docx/.doc]
         │
         ▼
  ┌─────────────────────────────────┐
  │  1. Pre-processament            │
  │     python-docx                 │
  │     - Extracció de contingut    │
  │     - Neteja bàsica             │
  └────────────┬────────────────────┘
               │
               ▼
  ┌─────────────────────────────────┐
  │  2. Anàlisi estructural         │
  │     spaCy + heurístiques        │
  │     - Identificació de seccions │
  │     - Detecció d'errors de cita │
  │     - Classificació bibliogràf. │
  └────────────┬────────────────────┘
               │
               ▼
  ┌─────────────────────────────────┐
  │  3. Aplicació d'estil           │
  │     python-docx + plantilla     │
  │     - Fonts i mides             │
  │     - Interlineat               │
  │     - Versaletes, cursives      │
  └────────────┬────────────────────┘
               │
               ▼
  ┌─────────────────────────────────┐
  │  4. Generació d'informe         │
  │     - Canvis aplicats           │
  │     - Alertes per revisió manual│
  │     - Elements incomplets       │
  └────────────┬────────────────────┘
               │
               ▼
  [Document corregit .docx + informe .md/.html]
```

---

## Fases d'implementació

### Fase 1 — Script de pre-processament (python-docx)

Correccions automàtiques d'alta confiança que no requereixen comprensió semàntica:

| Correcció                                                             | Implementació                   |
| --------------------------------------------------------------------- | ------------------------------- |
| Eliminar dobles/triples espais                                        | Regex sobre cada paràgraf       |
| Substituir cometes angleses `"..."` per llatines `«...»`              | Regex                           |
| Assegurar espai simple entre paràgrafs                                | Revisar `space_after` dels runs |
| Font cos del text → PT Serif 10, interlineat 1,1                      | Iterar paràgrafs                |
| Font notes al peu → PT Serif 8,5, interlineat senzill                 | Iterar `footnotes`              |
| Font títols nivell 1 → Open Sans 11 negrita                           | Detectar per estil Word         |
| Font títols nivell 2 (1.1, 1.2) → PT Serif 10 negrita                 | Detectar per format numèric     |
| Font títols nivell 3 (a., b.) → PT Serif 10 cursiva                   | Detectar per format lletra      |
| Encapçalament → Open Sans 8 negrita (autor dreta, nº InDret esquerra) | Modificar header                |

### Fase 2 — Anàlisi estructural (spaCy + heurístiques)

Tasques que requereixen comprensió del contingut:

**a) Identificació de seccions**
Enviar el text extret al LLM per identificar:

- Portada (títol, subtítol, autors, resum, abstract, paraules clau)
- Índex
- Cos del text (seccions, subseccions)
- Notes al peu
- Bibliografia

**b) Detecció de cognoms d'autor per versaletes**

- A la bibliografia i notes: els cognoms dels autors han d'anar en versaletes (small caps), no en majúscules ni cursiva.
- El LLM identifica quins termes són cognoms d'autors citats.

**c) Classificació d'entrades bibliogràfiques**
Cada entrada de la bibliografia s'envia al LLM per determinar el tipus:

- Monografia → format `COGNOM, Nom, *Títol*, editorial, any`
- Article de revista → format `COGNOM, «Títol», *Revista*, núm., any, pp.`
- Capítol d'obra col·lectiva → format `COGNOM, «Títol», en COGNOM (ed.), *Obra*, any, pp.`

I detectar errors de format (cometes incorrectes, manca de cursiva al títol, etc.)

**d) Comprovació de format de cites de jurisprudència**
Verificar que segueixen les pautes:

- STS, STSJ, SAP, SJPI, STC amb ECLI o Roj
- Jurisprudència TJUE

### Fase 3 — Aplicació de l'estil complet

Partir de `plantilla.docx` com a base i:

1. Injectar el contingut identificat a les seccions correctes
2. Aplicar els estils Word predefinits a la plantilla
3. Generar la portada (pàgina 1) amb l'estructura correcta
4. Generar l'índex (pàgina 2) amb la tipografia correcta
5. Aplicar versaletes als cognoms identificats pel LLM
6. Corregir les cometes bibliogràfiques

### Fase 4 — Informe de canvis

Produir un document `.md` o `.html` amb:

```markdown
## Informe de correcció — [nom_fitxer] — [data]

### Canvis aplicats automàticament

- [✓] Espais dobles eliminats (17 instàncies)
- [✓] Cometes «» aplicades (8 substitucions)
- [✓] Font cos del text → PT Serif 10
- [✓] Font notes al peu → PT Serif 8,5
- ...

### Alertes que requereixen revisió manual

- [!] No s'ha detectat abstract en anglès
- [!] Entrada bibliogràfica línia 45: possible manca de cursiva al títol
- [!] 3 cognoms d'autor en MAJÚSCULES (no versaletes) — revisar
- [!] Cita de jurisprudència línia 78: no segueix format ECLI/Roj
- ...

### Elements incomplets (afegir per InDret)

- [ ] Número d'edició InDret
- [ ] Dates de recepció i acceptació
- [ ] Paginació (a càrrec de l'equip d'edició)
```

---

## Stack tecnològic

| Component                     | Tecnologia                                                 |
| ----------------------------- | ---------------------------------------------------------- |
| Manipulació de documents Word | `python-docx`                                              |
| Identificació de seccions     | Heurístiques (paraules clau + mida de font)                |
| Detecció de noms d'autor      | `spaCy` (`es_core_news_lg`) — NER entitats PERSON          |
| Classificació bibliogràfica   | Regex sobre patrons estructurals                           |
| Cites jurisprudència          | Regex (STS, STC, ECLI, Roj)                               |
| Script principal              | Python 3.11+                                               |
| Interfície d'ús               | CLI (`python corrector.py article.docx`)                   |
| Informe de sortida            | Markdown o HTML                                            |

---

## Limitacions i consideracions

### Què es pot automatitzar amb alta fiabilitat

- Substitució de fonts i mides
- Interlineat i espaiat
- Dobles espais
- Cometes tipogràfiques
- Encapçalaments i peus de pàgina

### Què requereix LLM i revisió posterior

- Identificació de seccions en documents sense estils Word definits
- Versaletes en cognoms (el LLM pot cometre errors)
- Format de cites bibliogràfiques complexes

### Què NO es pot automatitzar

- Número d'edició d'InDret (l'assigna la redacció)
- Dates de recepció/acceptació (les afegeix InDret)
- Paginació final (la fa l'equip d'edició)
- Correcció de contingut (verificar que les cites són correctes)
- Traducció del títol a l'anglès/alemany si no existeix

---

## Flux de treball proposat per a l'editor

```
1. Rebre article via formulari indret.com/formulario/
2. Executar: python corrector.py article.docx
3. Obtenir: article_corregit.docx + informe.md
4. Revisar les alertes de l'informe
5. Aplicar correccions manuals residuals
6. Afegir número InDret i dates
7. Publicar
```

---

## Preguntes obertes per confirmar abans d'implementar

1. **Entorn d'execució**: El script s'executarà localment (Mac de l'editor) o en un servidor?
   el script s'hauria d'execitar en un servidor web finalment. Pero per començar a investigar i perfeccionar el sistema de moment ho farem en local
2. **Volum**: S'ha de processar d'un en un o en lots (per exemple, tots els articles d'una edició a la vegada)?
   Comencem processant d'un en un
3. **Integració web**: Es vol integrar amb el formulari de `indret.com/formulario/` de forma que el corregit es retorni automàticament per email, o és un procés offline?
   Ja pensarem quin será el timeline final. De moment centrem-nos en la eina
4. **Clau API**: Es disposa d'accés a l'API de Claude (Anthropic) per a la part semàntica, o cal plantejar una alternativa sense LLM?
   La clau API es de gemini, pero m ágradaria que em diguéssis quina alternativa sense LLM em pots pensar
5. **Fonts**: Les fonts PT Serif i Open Sans estan instal·lades al sistema on s'executarà el script?
   Les fonts estan a /articles_correc/tipos/
