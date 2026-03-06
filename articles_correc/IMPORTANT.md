❯ Ok, bona feina. Aquesta forma de treballar que hem fet servir per a aquest projecte, en que anaves guardant el progrés i la memoria i obrint noves sessions, es la estandard teva o si no com hauria de tipificar-ho per treballar
  així quan ho necessito? Potser creant una skill, plugin o prompt especific?

⏺ Bona pregunta. El que hem fet servir és el sistema d'auto-memòria que tinc incorporat: guardo notes a fitxers .md al directori de memòria del projecte i les carrego automàticament a cada sessió nova. És estàndard però informal — depèn
   que jo decideixi quan i què guardar.

  Si vols algo més estructurat i sistemàtic, tens disponible el sistema GSD (Get Stuff Done), que és exactament per a això. Tens una suite sencera de skills:

  ┌───────────────────┬─────────────────────────────────────────────────────────┐
  │       Skill       │                      Quan usar-la                       │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:new-project   │ Inicialitza un projecte nou amb PROJECT.md i ROADMAP.md │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:progress      │ Reprèn feina: mostra context i on estàs                 │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:plan-phase    │ Planifica la propera fase amb tasques detallades        │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:execute-phase │ Executa un pla amb agents en paral·lel                  │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:pause-work    │ Crea un handoff complet quan deixes feina a mitges      │
  ├───────────────────┼─────────────────────────────────────────────────────────┤
  │ gsd:resume-work   │ Restaura context complet en una sessió nova             │
  └───────────────────┴─────────────────────────────────────────────────────────┘

  Per al projecte InDret tal com treballem ara, et recomanaria:

  - Si continues igual → prou amb l'auto-memòria, funciona bé per a un script d'una sola persona.
  - Si escala (múltiples articles, servidor web, col·laboradors) → val la pena inicialitzar GSD amb /gsd:new-project per tenir roadmap, fases i historial de decisions documentats formalment.

  Vols que inicialitzem GSD per al projecte InDret, o prefereixes continuar com fins ara?

  #################

    Recorda: quan tornis, simplement obre una sessió nova i executa /gsd:progress — et portarà directament al punt on ets.