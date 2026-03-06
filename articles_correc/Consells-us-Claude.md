Algunas acciones posibles:

Activa MEMORY.md y pon las lecciones ahí, y mantén una separación clara de responsabilidades entre CLAUDE y MEMORY

Necesitas mejorar en dividir las instrucciones en partes más pequeñas. Y descríbelo más claramente (puedes pedirle a opus que evalúe qué tan bueno eres dando indicaciones)

Recopila cada vez que esto suceda el prompt, qué pasó y qué esperabas que pasara. Luego haz un análisis de esto una vez que tengas 10+ ejemplos (hazlo tú mismo, luego pregúntale a claude sin sesgarlo).

Ignora a los subagentes por ahora hasta que mejores en dar indicaciones y en la ingeniería de contexto (mantén las cosas simples)

Pídele a claude que reformule lo que entendió que era la tarea. A menudo el modelo podría malinterpretarte, o proporcionas información insuficiente, lo que lo obliga a asumir. Esta es una oportunidad para aclarar. Tengo en mi prompt que debe preguntar si no está seguro, y "exponer los puntos ciegos en lugar de autocompletar los huecos". Incluso puedes pedirle que use la herramienta AskUserQuestion (sabrá qué hacer). Muy útil.

Pídele que descomponga los problemas antes de lanzarse a resolverlos. Pídele que cree listas de tareas (ayuda a hacer cumplir el proceso y a no perder pasos).

Usa el modo Plan agresivamente. Siempre haz esto para tareas complicadas.

No lo obligues a usar el mega-max-ultra thinking en tareas fáciles, ya que esto tiende a romper la salida del modelo al hacerlo sobrepensar. Usa el pensamiento adaptativo. No uses prompts de instrucciones estrictas paso a paso con alto thinking. Son para el modo de bajo thinking.

Cada vez que falle, pídele a claude que escriba una regla para evitarlo. Luego, de vez en cuando, pídele a claude que revise todas las reglas y que limpie / combine / consolide, etc.

Pídele a claude que revise cómo percibe los archivos CLAUDE y MEMORY. A menudo es bastante revelador.

Ejecuta /insights para obtener un informe automático sobre cómo lo usas. A menudo también da sugerencias e instrucciones de mejora, una herramienta bastante chula.

Si puedes compartir algunos ejemplos reales, podría darte consejos menos genéricos.
