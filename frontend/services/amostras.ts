  import { Amostra } from "../types/amostra";

  const API_URL = "http://localhost:8080";

  export async function obterAmostras(): Promise<Amostra[]> {
      const response = await fetch(`${API_URL}/amostras`);

      if (!response.ok) {
          throw new Error("Erro ao obter amostras.");
      }

      return response.json();
  }
  export async function iniciarAnalise(id: number): Promise<void> {
      const response = await fetch(
          `${API_URL}/amostras/${id}/iniciar-analise`,
          {
              method: "POST",
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao iniciar análise."
          );
      }
  }

  export async function concluirAnalise(id: number): Promise<void> {
      const dataConclusao = new Date().toISOString().split("T")[0];

      const response = await fetch(
          `${API_URL}/amostras/${id}/concluir-analise`,
          {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({
                  dataConclusao,
              }),
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao concluir análise."
          );
      }
  }

  export async function rejeitarAmostra(id: number): Promise<void> {
      const response = await fetch(
          `${API_URL}/amostras/${id}/rejeitar`,
          {
              method: "POST",
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao rejeitar amostra."
          );
      }
  }

export async function atualizarTipo(
      id: number,
      tipo: string
  ): Promise<void> {
      const response = await fetch(
          `${API_URL}/amostras/${id}/tipo`,
          {
              method: "PUT",
              headers: {
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({
                  tipo,
              }),
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao atualizar tipo."
          );
      }
  }

  export async function atualizarResponsavel(
      id: number,
      responsavelTecnico: string
  ): Promise<void> {
      const response = await fetch(
          `${API_URL}/amostras/${id}/responsavel`,
          {
              method: "PUT",
              headers: {
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({
                  responsavelTecnico,
              }),
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao atualizar responsável."
          );
      }
  }

  export async function deletarAmostra(id: number): Promise<void> {
      const response = await fetch(
          `${API_URL}/amostras/${id}`,
          {
              method: "DELETE",
          }
      );

      if (!response.ok) {
          const erro = await response.json();

          throw new Error(
              erro.mensagem || "Erro ao excluir amostra."
          );
      }
  }
