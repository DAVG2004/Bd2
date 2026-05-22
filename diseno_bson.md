# Diseño de Base de Datos Documental (MongoDB)

Este documento detalla el diseño propuesto para migrar la estructura relacional de la galería de arte hacia un esquema documental optimizado en MongoDB, respondiendo a la necesidad de justificar el uso de documentos embebidos versus referencias.

## 1. Justificación de Diseño BSON: Embebidos vs Referencias

Al pasar de un modelo relacional (MySQL) a MongoDB, es fundamental aprovechar la flexibilidad del esquema. Para este proyecto, utilizaremos un **modelo híbrido** que combina ambos patrones según el patrón de acceso de la aplicación:

### A. Uso de Referencias (Relación Artista -> Obra)
- **Decisión:** Mantener `Artistas` y `Obras` en colecciones separadas y usar una referencia (`id_artista` en el documento de la obra).
- **Justificación:** En una galería de arte, el catálogo de obras se consulta frecuentemente de forma independiente al artista. Por ejemplo, un usuario puede buscar "todas las obras de tipo Escultura disponibles", ordenar obras por precio o filtrar por fechas, sin necesidad de cargar todos los datos biográficos de los artistas. Si hubiéramos embebido las obras dentro del documento del artista (como un arreglo `obras: []`), hacer consultas a nivel global de obras sería ineficiente y complicado. Por lo tanto, el uso de referencias es la opción más óptima.

### B. Uso de Documentos Embebidos (Atributos Polimórficos de Obras)
- **Decisión:** Embeber los detalles técnicos específicos de cada género directamente dentro del documento de la `Obra`.
- **Justificación:** En el diseño SQL original, existían 5 tablas separadas (`ceramica`, `escultura`, `fotografia`, `orferbreria`, `pintura`) que tenían una relación 1 a 1 con la tabla `obra`. Esto requería realizar múltiples *JOINs* para obtener los detalles completos de una obra dependiendo de su género.
En MongoDB, podemos usar el patrón **Polimórfico** embebiendo un subdocumento `detalles_tecnicos` dentro de cada obra. Dependiendo del género (ej. Pintura o Escultura), el subdocumento tendrá diferentes campos (ej. `tecnica` y `soporte` para Pintura; `material` y `peso` para Escultura). Esto elimina la necesidad de múltiples tablas/colecciones y permite recuperar toda la información de una obra en una única lectura de disco.

### C. Uso de Documentos Embebidos (Géneros del Artista)
- **Decisión:** Embeber los géneros de los que participa un artista como un arreglo simple de strings.
- **Justificación:** La tabla intermedia `genero_artista` y la tabla catálogo `genero` añaden complejidad innecesaria para datos que rara vez cambian. Embeber `generos: ["Pintura", "Escultura"]` en el documento del artista agiliza la lectura de su perfil.

---

## 2. Estructura de Colecciones Propuesta

### Colección: `artistas`
```json
{
  "_id": 70810283,
  "nombre": "albaricoke",
  "apellido": "munos",
  "email": "panchopansa43@gmail.com",
  "fecha_nacimiento": ISODate("2003-12-19T00:00:00Z"),
  "nacionalidad": "Nigeria",
  "usuario": "poche4",
  "telefono": 2147483647,
  "password_hash": "$2y$10$...",
  "foto_perfil": 0,
  "generos": ["Pintura", "Escultura"]
}
```

### Colección: `obras`
```json
{
  "_id": 11,
  "id_artista": 70810283,
  "nombre_archivo": "69ad6dbd1c5e6.jpg",
  "genero": "Escultura",
  "precio": 0,
  "fecha_publicacion": ISODate("2026-03-08T12:38:21Z"),
  "status": "disponible",
  "detalles_tecnicos": {
    "material": "Arcilla",
    "peso": 2.5,
    "alto": 30.0,
    "ancho": 15.0
  }
}
```

## Plan de Ejecución
A continuación, se ejecutará directamente el script de migración para inyectar estos datos en la base de datos `galeria_db_mongo` usando los datos del respaldo SQL provisto.
