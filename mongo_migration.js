// Archivo: mongo_migration.js
// Script para migrar datos de Artistas y Obras a MongoDB
// Ejecutar con: mongo < mongo_migration.js  (o mongosh < mongo_migration.js)

// Conectar a la base de datos (por defecto conectará a localhost:27017/test y luego cambiamos a galeria_db_mongo)
db = db.getSiblingDB('galeria_db_mongo');

// Limpiar colecciones si existen (para evitar duplicados en re-ejecuciones)
db.artistas.drop();
db.obras.drop();

// 1. Insertar Artistas
const artistas = [
  {
    _id: 1,
    nombre: "alfred",
    apellido: "gustavo",
    email: "susas@gamil.com",
    fecha_nacimiento: new Date("2016-03-02"),
    nacionalidad: "peruano",
    usuario: "alfredo",
    telefono: "41485652",
    password_hash: "papita",
    foto_perfil: "1211",
    generos: ["Pintura"]
  },
  { 
    _id: 30810283, 
    nombre: "jose", 
    apellido: "silva", 
    email: "panchopansa43@gmail.com", 
    fecha_nacimiento: null, 
    nacionalidad: "", 
    usuario: "poche", 
    telefono: "0", 
    password_hash: "$2y$10$cTC7XW5X2bvBMnQjV7mM1.bsES8LRrS7zUzV9ftfgazuXo.eCMdIi",
    generos: []
  },
  { 
    _id: 40810284, 
    nombre: "carlos", 
    apellido: "guerra", 
    email: "j6828611@gmail.com", 
    fecha_nacimiento: null, 
    nacionalidad: "", 
    usuario: "pocheeeee", 
    telefono: "0", 
    password_hash: "$2y$10$X6RhfXrWZKrbY.XIus0Ja./oCdLUMTPwzzTyq6YakBlBaHy4G2Q6W",
    generos: []
  },
  { 
    _id: 60810283, 
    nombre: "pepe", 
    apellido: "armandomonte", 
    email: "panchopansa43@gmail.com", 
    fecha_nacimiento: null, 
    nacionalidad: "peruano", 
    usuario: "poche3", 
    telefono: "2147483647", 
    password_hash: "$2y$10$cHxvR.7cEZSrD9IIhqb4AOxDxhelu8Vwy21DNQMnObLBXtHSkcNP.",
    generos: []
  },
  { 
    _id: 70810283, 
    nombre: "albaricoke", 
    apellido: "munos", 
    email: "panchopansa43@gmail.com", 
    fecha_nacimiento: new Date("2003-12-19"), 
    nacionalidad: "Nigeria", 
    usuario: "poche4", 
    telefono: "2147483647", 
    password_hash: "$2y$10$cJW8e/gJGbjf4WfaWxcoOONiJbclaIrPBA0YMZfZ07UwVpIFeAUnq",
    generos: ["Pintura", "Escultura"]
  }
];

db.artistas.insertMany(artistas);
print("Insertados " + artistas.length + " artistas.");

// 2. Insertar Obras (con detalles técnicos embebidos según género)
const obras = [
  {
    _id: 9,
    id_artista: 70810283,
    genero: "Pintura",
    nombre: "69ad6a8ec90af.jpg",
    precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:24:46Z"),
    status: "disponible",
    detalles_tecnicos: {
       tecnica: "Óleo",
       soporte: "Lienzo",
       alto: 100,
       ancho: 80
    }
  },
  {
    _id: 11,
    id_artista: 70810283,
    genero: "Escultura",
    nombre: "69ad6dbd1c5e6.jpg",
    precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:38:21Z"),
    status: "disponible",
    detalles_tecnicos: { 
       material: "Mármol",
       peso: 45.5,
       alto: 120,
       ancho: 50
    }
  },
  {
    _id: 12,
    id_artista: 70810283,
    genero: "Pintura",
    nombre: "69ad6f3b11db7_lasmeninas.jpg",
    precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:44:43Z"),
    status: "disponible",
    detalles_tecnicos: { 
       tecnica: "Acuarela",
       soporte: "Papel",
       alto: 50,
       ancho: 40
    }
  },
  {
    _id: 13,
    id_artista: 70810283,
    genero: "Pintura",
    nombre: "69ad6f6c1bc5e_mujer de perlas.jpg",
    precio: 0,
    fecha_publicacion: new Date("2026-03-08T12:45:32Z"),
    status: "disponible",
    detalles_tecnicos: { 
       tecnica: "Óleo",
       soporte: "Lienzo",
       alto: 60,
       ancho: 50
    }
  }
];

db.obras.insertMany(obras);
print("Insertadas " + obras.length + " obras.");

// 3. Crear índices para optimizar consultas frecuentes
db.obras.createIndex({ id_artista: 1 });
db.obras.createIndex({ genero: 1 });
db.obras.createIndex({ status: 1 });

print("Migración completada con éxito. Colecciones BSON generadas.");
