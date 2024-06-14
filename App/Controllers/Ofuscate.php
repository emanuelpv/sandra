<?php
// Desenvolvido por Sandra Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\PacientesModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;


class Ofuscate extends BaseController
{

    protected $solicitacoesSuporteModel;
    protected $pessoasModel;
    protected $organizacoesModel;
    protected $organizacao;
    protected $codOrganizacao;
    protected $validation;

    public function __construct()
    {

        $this->PacientesModel = new PacientesModel();
        $this->OrganizacoesModel = new OrganizacoesModel();
        $this->PessoasModel = new PessoasModel();
        $this->validation =  \Config\Services::validation();

        if ($_SERVER['SERVER_ADDR'] == '10.47.44.16') {

            $pacientes = $this->PacientesModel->pega_pacientes();
            $pessoas = $this->PessoasModel->pegaTudo();

            foreach ($pacientes as $key => $paciente) {
                $dadosPacientes['nomeExibicao'] = str_pad(substr($paciente->nomeExibicao, 0, 10), strlen($paciente->nomeExibicao), '*');
                $dadosPacientes['nomeCompleto'] = str_pad(substr($paciente->nomeCompleto, 0, 10), strlen($paciente->nomeCompleto), '*');
                $dadosPacientes['nomePrincipal'] = str_pad(substr($paciente->nomePrincipal, 0, 10), strlen($paciente->nomeComnomePrincipalpleto), '*');
                $dadosPacientes['codPlano'] = str_pad(substr($paciente->codPlano, 0, 4), strlen($paciente->codPlano), '*');
                $dadosPacientes['cpf'] = str_pad(substr($paciente->cpf, 0, 4), strlen($paciente->cpf), '*');
                $dadosPacientes['emailPessoal'] = 'paciente@paciente.com.br';
                $dadosPacientes['celular'] = '(81) 999999999';
                $dadosPacientes['endereco'] = 'Rua fake, nº 1';
                $dadosPacientes['nomeMae'] = 'Manoel Fake da Silva';
                $dadosPacientes['nomePai'] = 'Maria Fake da Silva';
                $dadosPacientes['codProntuario'] = str_pad(substr($paciente->codProntuario, 0, 4), strlen($paciente->codProntuario), '*');
                $dadosPacientes['senha'] = 'ff545fbc007b72dd24733634f16b3ccd8063e9d6dfa32b3522d5cbd25ff9f4bc';
                $this->PacientesModel->update($paciente->codPaciente, $dadosPacientes);
            }

            

            foreach ($pessoas as $key => $pessoa) {
                $dadosPessoas['senha'] = 'ff545fbc007b72dd24733634f16b3ccd8063e9d6dfa32b3522d5cbd25ff9f4bc';
                $this->PessoasModel->update($pessoa->codPessoa, $dadosPessoas);
            }


            
            //atualiza login médico com agenda

            $agendamentos = $this->PacientesModel->medicoMaiorAgendaHj();
            $dadosMedico['codPessoa']=$agendamentos->codEspecialista;
            $dadosMedico['conta']='medico';
            $this->PessoasModel->update($dadosMedico['codPessoa'], $dadosMedico);


            $dadosAdm['codPessoa']=1173;
            $dadosAdm['conta']='adminiatrador';
            $this->PessoasModel->update($dadosAdm['codPessoa'], $dadosAdm);
            


            $this->OrganizacoesModel->ofuscacaoDesativaNotificacao();

        }
        print "Rotina executada com sucesso"; 
        exit();
    }
}
