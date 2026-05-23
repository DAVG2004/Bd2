# Consultas MongoDB — Galería de Arte
**Base de datos:** `galeria_db_mongo`

---

## 1. Esquema de Colecciones

### Colección: `artistas`

| Campo              | Tipo       | Descripción                                      |
|--------------------|------------|--------------------------------------------------|
| `_id`              | Number     | Identificador único del artista (heredado de SQL)|
| `nombre`           | String     | Nombre del artista                               |
| `apellido`         | String     | Apellido del artista                             |
| `email`            | String     | Correo electrónico                               |
| `fecha_nacimiento` | Date / null| Fecha de nacimiento                              |
| `nacionalidad`     | String     | País de origen                                   |
| `usuario`          | String     | Nombre de usuario en la plataforma               |
| `telefono`         | String     | Número de teléfono                               |
| `password_hash`    | String     | Contraseña hasheada (bcrypt)                     |
| `foto_perfil`      | String     | Referencia a foto de perfil                      |
| `generos`          | Array\<String\> | Géneros artísticos que practica (**embebido**) |

**Ejemplo de documento:**
```json
{
  "_id": 70810283,
  "nombre": "albaricoke",
  "apellido": "munos",
  "email": "panchopansa43@gmail.com",
  "fecha_nacimiento": ISODate("2003-12-19T00:00:00Z"),
  "nacionalidad": "Nigeria",
  "usuario": "poche4",
  "telefono": "2147483647",
  "password_hash": "$2y$10$cJW8e/gJGbjf4WfaWxcoOONiJbclaIrPBA0YMZfZ07UwVpIFeAUnq",
  "generos": ["Pintura", "Escultura"]
}
```

---

### Colección: `obras`

| Campo                  | Tipo    | Descripción                                            |
|------------------------|---------|--------------------------------------------------------|
| `_id`                  | Number  | Identificador único de la obra (heredado de SQL)       |
| `id_artista`           | Number  | Referencia al `_id` del artista (referencia manual)    |
| `genero`               | String  | Género artístico (Pintura, Escultura, etc.)            |
| `nombre`               | String  | Nombre del archivo de imagen                           |
| `precio`               | Number  | Precio de la obra                                      |
| `fecha_publicacion`    | Date    | Fecha y hora de publicación                            |
| `status`               | String  | Estado de la obra (`"disponible"`, `"vendido"`, etc.)  |
| `detalles_tecnicos`    | Object  | Subdocumento polimórfico según el género (**embebido**)|

**Subdocumento `detalles_tecnicos` — Pintura:**
```json
{
  "tecnica": "Óleo",
  "soporte": "Lienzo",
  "alto": 100,
  "ancho": 80
}
```

**Subdocumento `detalles_tecnicos` — Escultura:**
```json
{
  "material": "Mármol",
  "peso": 45.5,
  "alto": 120,
  "ancho": 50
}
```

**Ejemplo de documento completo:**
```json
{
  "_id": 11,
  "id_artista": 70810283,
  "genero": "Escultura",
  "nombre": "69ad6dbd1c5e6.jpg",
  "precio": 0,
  "fecha_publicacion": ISODate("2026-03-08T12:38:21Z"),
  "status": "disponible",
  "detalles_tecnicos": {
    "material": "Mármol",
    "peso": 45.5,
    "alto": 120,
    "ancho": 50
  }
}
```

---

## 2. Scripts de Inserción

> Ejecutar con: `mongosh < mongo_migration.js`

### 2.1 Selección de base de datos y limpieza
```js
db = db.getSiblingDB('galeria_db_mongo');

// Limpiar colecciones para evitar duplicados en re-ejecuciones
db.artistas.drop();
db.obras.drop();
```

### 2.2 Inserción de Artistas
```js
const artistas = [
  {
    _id: 1,
    nombre: "alfred", apellido: "gustavo",
    email: "susas@gamil.com",
    fecha_nacimiento: new Date("2016-03-02"),
    nacionalidad: "peruano", usuario: "alfredo",
    telefono: "41485652", password_hash: "papita",
    foto_perfil: "1211", generos: ["Pintura"]
  },
  {
    _id: 30810283,
    nombre: "jose", apellido: "silva",
    email: "panchopansa43@gmail.com",
    fecha_nacimiento: null, nacionalidad: "",
    usuario: "poche", telefono: "0",
    password_hash: "$2y$10$cTC7XW5X2bvBMnQjV7mM1.bsES8LRrS7zUzV9ftfgazuXo.eCMdIi",
    generos: []
  },
  {
    _id: 40810284,
    nombre: "carlos", apellido: "guerra",
    email: "j6828611@gmail.com",
    fecha_nacimiento: null, nacionalidad: "",
    usuario: "pocheeeee", telefono: "0",
    password_hash: "$2y$10$X6RhfXrWZKrbY.XIus0Ja./oCdLUMTPwzzTyq6YakBlBaHy4G2Q6W",
    generos: []
  },
  {
    _id: 60810283,
    nombre: "pepe", apellido: "armandomonte",
    email: "panchopansa43@gmail.com",
    fecha_nacimiento: null, nacionalidad: "peruano",
    usuario: "poche3", telefono: "2147483647",
    password_hash: "$2y$10$cHxvR.7cEZSrD9IIhqb4AOxDxhelu8Vwy21DNQMnObLBXtHSkcNP.",
    generos: []
  },
  {
    _id: 70810283,
    nombre: "albaricoke", apellido: "munos",
    email: "panchopansa43@gmail.com",
    fecha_nacimiento: new Date("2003-12-19"),
    nacionalidad: "Nigeria", usuario: "poche4",
    telefono: "2147483647",
    password_hash: "$2y$10$cJW8e/gJGbjf4WfaWxcoOONiJbclaIrPBA0YMZfZ07UwVpIFeAUnq",
    generos: ["Pintura", "Escultura"]
  }
];

db.artistas.insertMany(artistas);
print("Insertados " + artistas.length + " artistas.");
```

### 2.3 Inserción de Obras
```js
const obras = [
  {
    _id: 9, id_artista: 70810283, genero: "Pintura",
    nombre: "69ad6a8ec90af.jpg", precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:24:46Z"),
    status: "disponible",
    detalles_tecnicos: { tecnica: "Óleo", soporte: "Lienzo", alto: 100, ancho: 80 }
  },
  {
    _id: 11, id_artista: 70810283, genero: "Escultura",
    nombre: "69ad6dbd1c5e6.jpg", precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:38:21Z"),
    status: "disponible",
    detalles_tecnicos: { material: "Mármol", peso: 45.5, alto: 120, ancho: 50 }
  },
  {
    _id: 12, id_artista: 70810283, genero: "Pintura",
    nombre: "69ad6f3b11db7_lasmeninas.jpg", precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:44:43Z"),
    status: "disponible",
    detalles_tecnicos: { tecnica: "Acuarela", soporte: "Papel", alto: 50, ancho: 40 }
  },
  {
    _id: 13, id_artista: 70810283, genero: "Pintura",
    nombre: "69ad6f6c1bc5e_mujer de perlas.jpg", precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:45:32Z"),
    status: "disponible",
    detalles_tecnicos: { tecnica: "Óleo", soporte: "Lienzo", alto: 60, ancho: 50 }
  }
];

db.obras.insertMany(obras);
print("Insertadas " + obras.length + " obras.");
```

### 2.4 Creación de Índices
```js
db.obras.createIndex({ id_artista: 1 });
db.obras.createIndex({ genero: 1 });
db.obras.createIndex({ status: 1 });
```

---

## 3. Consultas con Aggregation Framework

### 3.1 Filtrar obras por disponibilidad
Obtener todas las obras con status `"disponible"`:
```js
db.obras.aggregate([
  {
    $match: { status: "disponible" }
  },
  {
    $project: {
      nombre: 1,
      genero: 1,
      precio: 1,
      status: 1,
      fecha_publicacion: 1
    }
  }
]);
```

---

### 3.2 Filtrar obras por género
Obtener todas las obras del género `"Pintura"`:
```js
db.obras.aggregate([
  {
    $match: { genero: "Pintura" }
  },
  {
    $project: {
      nombre: 1,
      precio: 1,
      status: 1,
      detalles_tecnicos: 1
    }
  }
]);
```

---

### 3.3 Filtrar obras por precio
Obtener obras con precio mayor a 0 (obras no gratuitas), ordenadas de menor a mayor:
```js
db.obras.aggregate([
  {
    $match: { precio: { $gt: 0 } }
  },
  {
    $sort: { precio: 1 }
  },
  {
    $project: {
      nombre: 1,
      genero: 1,
      precio: 1,
      status: 1
    }
  }
]);
```

---

### 3.4 Filtrar por género Y disponibilidad combinados
Obtener obras de Pintura que estén disponibles:
```js
db.obras.aggregate([
  {
    $match: {
      genero: "Pintura",
      status: "disponible"
    }
  },
  {
    $project: {
      nombre: 1,
      precio: 1,
      detalles_tecnicos: 1
    }
  }
]);
```

---

### 3.5 Contar obras por género (agrupación)
Cuántas obras existen por cada género:
```js
db.obras.aggregate([
  {
    $group: {
      _id: "$genero",
      total_obras: { $sum: 1 },
      precio_promedio: { $avg: "$precio" }
    }
  },
  {
    $sort: { total_obras: -1 }
  }
]);
```

---

### 3.6 Obras disponibles con datos del artista (lookup/join)
Obtener obras disponibles junto con el nombre del artista:
```js
db.obras.aggregate([
  {
    $match: { status: "disponible" }
  },
  {
    $lookup: {
      from: "artistas",
      localField: "id_artista",
      foreignField: "_id",
      as: "artista"
    }
  },
  {
    $unwind: "$artista"
  },
  {
    $project: {
      nombre: 1,
      genero: 1,
      precio: 1,
      status: 1,
      "artista.nombre": 1,
      "artista.apellido": 1,
      "artista.usuario": 1
    }
  }
]);
```

---

### 3.7 Resumen completo: género, disponibilidad y precio
Pipeline que agrupa obras disponibles por género mostrando precio mínimo, máximo y promedio:
```js
db.obras.aggregate([
  {
    $match: { status: "disponible" }
  },
  {
    $group: {
      _id: "$genero",
      cantidad: { $sum: 1 },
      precio_minimo:  { $min: "$precio" },
      precio_maximo:  { $max: "$precio" },
      precio_promedio: { $avg: "$precio" }
    }
  },
  {
    $sort: { cantidad: -1 }
  }
]);
```
