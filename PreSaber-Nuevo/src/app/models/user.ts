export class User {
    constructor(public id,
                public rol_id_fk,
                public nombre: string,
                public documento: string,
                public apellido: string,
                public correo: string,
                public contrasena: string,
                public salt: string,
                public codigo_activacion: string,
                public imagen: string,
                public fecha_creacion,
                public fecha_actualizacion,
                public deletedAt,
                public rol_descripcion,
                public estado: string) {
    }
}
