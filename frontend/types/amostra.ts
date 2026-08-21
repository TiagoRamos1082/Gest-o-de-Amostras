export interface Amostra {
    id: number;
    codigo: string;
    tipo: string;
    status: string;
    responsavel_tecnico: string | null;
    data_recebimento: string;
    data_conclusao: string | null;
    bloqueada: number;
}
