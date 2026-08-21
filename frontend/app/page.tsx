"use client";

import { useEffect, useState } from "react";
import { Amostra } from "../types/amostra";
import {
    obterAmostras,
    iniciarAnalise,
    concluirAnalise,
    rejeitarAmostra,
    atualizarTipo,
    atualizarResponsavel,
    deletarAmostra,
} from "../services/amostras";

export default function Home() {
    const [amostras, setAmostras] = useState<Amostra[]>([]);
    const [statusFiltro, setStatusFiltro] = useState("");
    const [tipoFiltro, setTipoFiltro] = useState("");
    const [erro, setErro] = useState("");
    const [editandoId, setEditandoId] = useState<number | null>(null);
    const [novoTipo, setNovoTipo] = useState("");
    const [novoResponsavel, setNovoResponsavel] = useState("");

    useEffect(() => {
        carregarAmostras();
    }, []);

    async function carregarAmostras() {
        try {
            const resultado = await obterAmostras();

            setAmostras(resultado);
        } catch (error) {
            console.error(error);
        }
    }

    async function handleDeletar(id: number) {
        try {
            setErro("");

            const confirmou = window.confirm(
                "Tem certeza que deseja excluir esta amostra?"
            );

            if (!confirmou) {
                return;
            }

            await deletarAmostra(id);
            await carregarAmostras();
        } catch (error) {
            if (error instanceof Error) {
                setErro(error.message);
            }
        }
    }

    async function handleIniciarAnalise(id: number) {
        try {
            setErro("");

            await iniciarAnalise(id);
            await carregarAmostras();
        } catch (error) {
            if (error instanceof Error) {
                setErro(error.message);
            }
        }
    }

    async function handleConcluirAnalise(id: number) {
        try {
            setErro("");

            await concluirAnalise(id);
            await carregarAmostras();
        } catch (error) {
            if (error instanceof Error) {
                setErro(error.message);
            }
        }
    }

    async function handleRejeitar(id: number) {
        try {
            setErro("");

            await rejeitarAmostra(id);
            await carregarAmostras();
        } catch (error) {
            if (error instanceof Error) {
                setErro(error.message);
            }
        }
    }

    async function salvarEdicao(id: number) {
        try {
            setErro("");

            await atualizarTipo(id, novoTipo);

            await atualizarResponsavel(
                id,
                novoResponsavel
            );

            setEditandoId(null);

            await carregarAmostras();
        } catch (error) {
            if (error instanceof Error) {
                setErro(error.message);
            }
        }
    }

    function iniciarEdicao(amostra: Amostra) {
        setErro("");
        setEditandoId(amostra.id);
        setNovoTipo(amostra.tipo);
        setNovoResponsavel(amostra.responsavel_tecnico ?? "");
    }

    const amostrasFiltradas = amostras.filter((amostra) => {
        const correspondeStatus =
            statusFiltro === "" ||
            amostra.status === statusFiltro;

        const correspondeTipo =
            tipoFiltro === "" ||
            amostra.tipo === tipoFiltro;

        return correspondeStatus && correspondeTipo;
    });

    return (
        <main>
        <h1>Gestão de Amostras</h1>

        {erro && (
            <p>
                {erro}
            </p>
        )}

            <div>
                <label>
                    Status:
                    <select
                        value={statusFiltro}
                        onChange={(event) =>
                            setStatusFiltro(event.target.value)
                        }
                    >
                        <option value="">Todos</option>
                        <option value="Recebida">Recebida</option>
                        <option value="EmAnalise">Em Análise</option>
                        <option value="Concluida">Concluída</option>
                        <option value="Rejeitada">Rejeitada</option>
                    </select>
                </label>

                <label>
                    Tipo:
                    <select
                        value={tipoFiltro}
                        onChange={(event) =>
                            setTipoFiltro(event.target.value)
                        }
                    >
                        <option value="">Todos</option>
                        <option value="Água">Água</option>
                        <option value="Solo">Solo</option>
                        <option value="Ar">Ar</option>
                        <option value="Efluente">Efluente</option>
                    </select>
                </label>
            </div>

            <br />

            <a href="/amostras/nova">
                Nova Amostra
            </a>

            <br />
            <br />

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Recebimento</th>
                        <th>Conclusão</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    {amostrasFiltradas.map((amostra) => (
                        <tr key={amostra.id}>
                            <td>{amostra.codigo}</td>
                            <td>
                                {editandoId === amostra.id ? (
                                    <select
                                        value={novoTipo}
                                        onChange={(event) =>
                                            setNovoTipo(event.target.value)
                                        }
                                    >
                                        <option value="Água">Água</option>
                                        <option value="Solo">Solo</option>
                                        <option value="Ar">Ar</option>
                                        <option value="Efluente">Efluente</option>
                                    </select>
                                ) : (
                                    amostra.tipo
                                )}
                            </td>
                            <td>{amostra.status}</td>
                            <td>
                                {editandoId === amostra.id ? (
                                    <input
                                        type="text"
                                        value={novoResponsavel}
                                        onChange={(event) =>
                                            setNovoResponsavel(event.target.value)
                                        }
                                    />
                                ) : (
                                    amostra.responsavel_tecnico ?? "-"
                                )}
                            </td>
                            <td>{amostra.data_recebimento}</td>
                            <td>
                                {amostra.data_conclusao ?? "-"}
                        </td>
                        <td>
                          <button
                              onClick={() => handleDeletar(amostra.id)}
                          >
                              Excluir
                          </button>
                            {amostra.status !== "Concluida" &&
                                amostra.status !== "Rejeitada" && (
                                    <>
                                        {editandoId === amostra.id ? (
                                            <>
                                                <button
                                                    onClick={() => salvarEdicao(amostra.id)}
                                                >
                                                    Salvar
                                                </button>

                                                <button
                                                    onClick={() => setEditandoId(null)}
                                                >
                                                    Cancelar
                                                </button>
                                            </>
                                        ) : (
                                            <button
                                                onClick={() => iniciarEdicao(amostra)}
                                            >
                                                Editar
                                            </button>
                                        )}

                                        {amostra.status === "Recebida" && (
                                            <>
                                                <button
                                                    onClick={() =>
                                                        handleIniciarAnalise(amostra.id)
                                                    }
                                                >
                                                    Iniciar análise
                                                </button>

                                                <button
                                                    onClick={() =>
                                                        handleRejeitar(amostra.id)
                                                    }
                                                >
                                                    Rejeitar
                                                </button>
                                            </>
                                        )}

                                        {amostra.status === "EmAnalise" && (
                                            <>
                                                <button
                                                    onClick={() =>
                                                        handleConcluirAnalise(amostra.id)
                                                    }
                                                >
                                                    Concluir
                                                </button>

                                                <button
                                                    onClick={() =>
                                                        handleRejeitar(amostra.id)
                                                    }
                                                >
                                                    Rejeitar
                                                </button>
                                            </>
                                        )}
                                    </>
                                )}
                        </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </main>
    );
}
