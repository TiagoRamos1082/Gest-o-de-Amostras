"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";

export default function NovaAmostra() {
    const router = useRouter();

    const [tipo, setTipo] = useState("");
    const [responsavelTecnico, setResponsavelTecnico] = useState("");
    const [dataRecebimento, setDataRecebimento] = useState("");
    const [erro, setErro] = useState("");
    const [carregando, setCarregando] = useState(false);

    async function cadastrarAmostra(event: FormEvent) {
        event.preventDefault();

        setErro("");
        setCarregando(true);

        try {
            const response = await fetch(
                "http://localhost:8080/amostras",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        tipo,
                        responsavelTecnico,
                        dataRecebimento,
                    }),
                }
            );

            if (!response.ok) {
                throw new Error("Erro ao cadastrar amostra.");
            }

            router.push("/");
        } catch (error) {
            setErro(
                error instanceof Error
                    ? error.message
                    : "Erro ao cadastrar amostra."
            );
        } finally {
            setCarregando(false);
        }
    }

    return (
        <main>
            <h1>Nova Amostra</h1>

            <form onSubmit={cadastrarAmostra}>
                <div>
                    <label htmlFor="tipo">
                        Tipo
                    </label>

                    <select
                        id="tipo"
                        value={tipo}
                        onChange={(event) =>
                            setTipo(event.target.value)
                        }
                        required
                    >
                        <option value="">
                            Selecione
                        </option>

                        <option value="Água">
                            Água
                        </option>

                        <option value="Solo">
                            Solo
                        </option>

                        <option value="Ar">
                            Ar
                        </option>

                        <option value="Efluente">
                            Efluente
                        </option>
                    </select>
                </div>

                <div>
                    <label htmlFor="responsavelTecnico">
                        Responsável Técnico
                    </label>

                    <input
                        id="responsavelTecnico"
                        type="text"
                        value={responsavelTecnico}
                        onChange={(event) =>
                            setResponsavelTecnico(
                                event.target.value
                            )
                        }
                    />
                </div>

                <div>
                    <label htmlFor="dataRecebimento">
                        Data de Recebimento
                    </label>

                    <input
                        id="dataRecebimento"
                        type="date"
                        value={dataRecebimento}
                        onChange={(event) =>
                            setDataRecebimento(
                                event.target.value
                            )
                        }
                        required
                    />
                </div>

                {erro && (
                    <p>{erro}</p>
                )}

                <button
                    type="submit"
                    disabled={carregando}
                >
                    {carregando
                        ? "Cadastrando..."
                        : "Cadastrar"}
                </button>
            </form>

            <br />

            <button
                type="button"
                onClick={() => router.push("/")}
            >
                Voltar
            </button>
        </main>
    );
}
