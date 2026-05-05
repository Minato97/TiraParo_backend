# TiraParo — Documentación de la API
> Base URL: `https://<ngrok-url>/api`  
> Auth: Bearer Token (Sanctum)  
> Content-Type: `application/json`

---

## Auth

### Registro
```
POST /auth/register
```
Body:
```json
{
  "nombres": "Juan",
  "apellido_paterno": "Pérez",
  "apellido_materno": "García",
  "email": "juan@email.com",
  "password": "123456",
  "password_confirmation": "123456",
  "colonia": "Roma Norte",
  "alcaldia": "Cuauhtémoc"
}
```
Respuesta `201`:
```json
{
  "user": { "id": 1, "nombres": "Juan", "nivel": 1, "puntos_totales": 0, ... },
  "token": "1|abc123..."
}
```

### Login
```
POST /auth/login
```
Body: `{ "email": "...", "password": "..." }`

### Perfil (requiere token)
```
GET /auth/profile
Authorization: Bearer {token}
```

### Actualizar perfil
```
PUT /auth/profile
```
Body (campos opcionales): `nombres`, `colonia`, `alcaldia`, `password`+`password_confirmation`

### Logout
```
POST /auth/logout
POST /auth/logout-all
```

---

## Catálogos (públicos)

### Categorías de residuos
```
GET /categorias
GET /categorias/{ml_label}   — e.g. /categorias/plastic
```
Respuesta:
```json
[
  {
    "id": 1,
    "nombre": "Plástico",
    "tipo_destino": "reciclaje",
    "ml_label": "plastic",
    "icono_sf": "drop.fill",
    "color_hex": "#2196F3",
    "puntos_base": 30,
    "co2_por_kg": 1.5,
    "instrucciones": "Enjuaga el envase..."
  }
]
```

### Centros de destino
```
GET /centros
GET /centros/{id}
```
Filtros opcionales:
```
GET /centros?latitud=19.41&longitud=-99.17&radio_km=5
GET /centros?tipo=reciclaje
GET /centros?material=plastic
GET /centros?alcaldia=Cuauhtémoc
```
Respuesta incluye campo `distancia_km` cuando se envían coordenadas.

Tipos: `reciclaje` | `donacion` | `composta` | `reutilizacion`

---

## Escaneos (requiere token)

### Crear escaneo (30% de puntos inmediatos)
```
POST /escaneos
```
Body:
```json
{
  "ml_label": "plastic",
  "confianza": 0.91,
  "foto_escaneo": "data:image/jpeg;base64,/9j/...",
  "latitud": 19.4178,
  "longitud": -99.1603,
  "colonia": "Roma Norte",
  "alcaldia": "Cuauhtémoc"
}
```
Respuesta `201`:
```json
{
  "escaneo": { "id": 5, "estado": "pendiente", "puntos_escaneo": 9, ... },
  "puntos_ganados": 9,
  "mensaje": "¡+9 puntos! Sube la foto de evidencia para ganar 21 más.",
  "badges_nuevos": []
}
```

### Subir evidencia (70% de puntos restantes)
```
POST /escaneos/{id}/evidencia
```
Body:
```json
{
  "foto_evidencia": "data:image/jpeg;base64,/9j/...",
  "latitud": 19.4178,
  "longitud": -99.1603,
  "centro_destino_id": 1,
  "metodo_validacion": "geolocalizacion"
}
```
`metodo_validacion`: `geolocalizacion` | `foto` | `comunidad`

Respuesta:
```json
{
  "escaneo": { "id": 5, "estado": "validado", ... },
  "puntos_ganados": 21,
  "mensaje": "¡+21 puntos! Escaneo validado correctamente.",
  "badges_nuevos": [{ "nombre": "Primer Tiro", ... }]
}
```

### Historial de escaneos
```
GET /escaneos?page=1
```

### Detalle de escaneo
```
GET /escaneos/{id}
```

### Escaneos pendientes de validación comunitaria
```
GET /escaneos/pendientes-comunidad
```

### Votar evidencia de otro usuario
```
POST /escaneos/{id}/votar
Body: { "voto": "valido" }   // o "invalido"
```
Con 3 votos válidos → el escaneo se valida y el dueño recibe los puntos.

---

## Perfil e Impacto (requiere token)

### Dashboard de impacto ambiental
```
GET /perfil/impacto
```
Respuesta:
```json
{
  "usuario": {
    "nombre_nivel": "Guardián",
    "nivel": 3,
    "puntos_totales": 847,
    "puntos_semana": 120,
    "racha_dias": 5
  },
  "impacto": {
    "co2_evitado_kg": 2.34,
    "agua_ahorrada_litros": 183.5,
    "arboles_equivalentes": 0.11,
    "frase_co2": "Has evitado 2.3 kg de CO₂",
    "frase_arboles": "Equivale a plantar 0 árbol(es)",
    "frase_agua": "Has ahorrado 184 litros de agua"
  },
  "estadisticas": {
    "total_escaneos": 47,
    "escaneos_validados": 38,
    "por_categoria": [...]
  },
  "actividad_semanal": [...]
}
```

### Badges del usuario
```
GET /perfil/badges
```

### Historial paginado
```
GET /perfil/historial?page=1
```

---

## Mascota Virtual (requiere token)

### Ver mascota
```
GET /mascota
```
Respuesta:
```json
{
  "mascota": {
    "nombre": "Plantita",
    "tipo": "planta",
    "nivel_salud": 75,
    "escaneos_semana": 3,
    "ultimo_escaneo_at": "2026-05-04T10:00:00"
  },
  "estado": "bien",
  "mensaje": "Voy bien, ¡pero no me abandones! Escanea algo hoy 🌱"
}
```
Estados: `excelente` (≥80) | `bien` (≥50) | `necesita_ayuda` (≥20) | `critico` (<20)

### Actualizar mascota
```
PUT /mascota
Body: { "nombre": "Verdín", "tipo": "criatura" }
```

---

## Comunidad (requiere token)

### Mapa de impacto por colonia
```
GET /comunidad/colonias?mes=5&anio=2026
```

### Leaderboard
```
GET /comunidad/leaderboard?tipo=global
GET /comunidad/leaderboard?tipo=semana
GET /comunidad/leaderboard?tipo=alcaldia&alcaldia=Cuauhtémoc
```

### Retos comunitarios activos
```
GET /comunidad/retos
```
Respuesta:
```json
[
  {
    "titulo": "Mayo Verde CDMX",
    "meta_kg": 500,
    "actual_kg": 47.3,
    "porcentaje": 9.5,
    "completado": false,
    "dias_restantes": 27
  }
]
```

### Muro de evidencias (feed)
```
GET /comunidad/muro?page=1
```

### Stats globales de la app
```
GET /comunidad/stats
```
Respuesta:
```json
{
  "total_escaneos_validados": 1247,
  "total_usuarios": 312,
  "co2_evitado_kg_total": 847.3,
  "kg_reciclados_mes": 234.1,
  "arboles_equivalentes": 39.0
}
```

---

## Sistema de Puntos

| Acción | Puntos |
|--------|--------|
| Escanear (sin evidencia) | 30% de puntos_base |
| Subir evidencia (foto/GPS) | 70% de puntos_base |
| Validación comunitaria aprobada | 70% de puntos_base |

### Puntos base por categoría

| Material | ml_label | Puntos base |
|----------|----------|-------------|
| Metal/Aluminio | metal | 40 |
| Textil | textile | 35 |
| Plástico | plastic | 30 |
| Orgánicos | food_organics | 25 |
| Vidrio | glass | 25 |
| Cartón | cardboard | 20 |
| Vegetación | vegetation | 20 |
| Papel | paper | 15 |
| Residuo general | trash | 10 |

### Niveles de usuario

| Nivel | Nombre | Puntos |
|-------|--------|--------|
| 1 | Principiante | 0–99 |
| 2 | Reciclador | 100–499 |
| 3 | Guardián | 500–1,999 |
| 4 | Héroe del Planeta | 2,000+ |

---

## Errores comunes

| Código | Significado |
|--------|-------------|
| 401 | Token inválido o expirado |
| 403 | No autorizado (cuenta inactiva) |
| 422 | Error de validación — revisa el body |
| 404 | Recurso no encontrado |

---

## Setup y ngrok

```bash
# Levantar backend
bash setup.sh

# Exponer con ngrok
ngrok http 8000

# La URL ngrok (e.g. https://abc123.ngrok-free.app)
# úsala como BASE_URL en la app iOS
```

> **Importante:** Cambia la URL base en la app cada vez que reinicies ngrok, ya que la URL cambia en el plan gratuito.
