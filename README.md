
# dwd API

Repositorio hecho en base al aprendizaje obtenido segun la prueba tecnica:

"Dancing With Death"

La prueba esta escrita en ingles y consiste en dos partes, a continuacion traducire dichas partes.

===============================================================

**Parte 1:** Se debe crear una _API REST_ para el agendamiento de citas para tener un baile con la muerte. La API debe ser implementada como un CRUD que mantiene la informacion de las citas, la cual sera usada en la parte 2 con un cliente web.

- El sistema no debe permitir agendar mas de una cita por hora.
- El horario de agendamiento debe ser entre horas de oficina (Desde 9 AM hasta 6 PM de Lunes a Viernes) a lo largo de todo el año.
- La muerte es muy quisquillosa con su agenda, asi que cada cita debe contar con una fecha, hora de inicio e informacion de contacto (Como un e-mail).
- Solo se puede agendar citas de 1 hora de duracion, mas seria sin sentido. Menos seria muy traumatico.

**Parte 2:** Crear un _Cliente Web_ para la API creada.

- La primera interfaz debe mostrar un selector de fecha mostrando el mes actual.
- Al seleccionar cualquier fecha, las horas libres deberan mostrarse en la pantalla para ser seleccionadas.

La prueba debe de completarse en un maximo de 7 dias y la interfaz debe estar en ingles.

===============================================================

**Comentarios:** Esta prueba fue mi primera vez trabajando con una API, por ende me tomo bastante tiempo aprender las cosas que desarrolle aqui, no cumpli con la restriccion de los 7 dias y el ultimo punto de la parte 2 no encontre una forma de completarlo, por ende lo ignore, sin embargo, el resto de cosas quedaron implementadas. **Este repositorio contiene la Parte 1 de la prueba.**

Para correr este proyecto es necesario tener todos los archivos y correr los comandos a continuacion:

**Node:**
```node_modules
npm init -y
npm install
```

**TailwindCSS:**
```twconfig
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```