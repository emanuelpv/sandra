<?php
// Desenvolvido por TellMeeting Sistemas

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Services;

use App\Models\LogsModel;
use App\Models\PessoasModel;
use App\Models\OrganizacoesModel;

use App\Models\TesteModel;
use App\Models\AtendimentosPrescricoesModel;

class Localizador extends BaseController
{

	protected $TesteModel;
	protected $pessoasModel;
	protected $OrganizacoesModel;
	protected $organizacao;
	protected $codOrganizacao;
	protected $validation;

	public function __construct()
	{

		$this->AtendimentosPrescricoesModel = new AtendimentosPrescricoesModel();
		$this->OrganizacoesModel = new OrganizacoesModel();
		$this->LogsModel = new LogsModel(); // $this->LogsModel->inserirLog('descrição da ocorrencia',$codPessoa);
		$this->validation =  \Config\Services::validation();
		$this->codOrganizacao = session()->codOrganizacao;
		$this->Organizacao =  $this->OrganizacoesModel->pegaOrganizacao($this->codOrganizacao);


		$this->validation =  \Config\Services::validation();
		helper(['form', 'url']);

		$configuracao = config('App');
		session()->set('codOrganizacao', $configuracao->codOrganizacao);
		$codOrganizacao = $configuracao->codOrganizacao;


		$dadosOrganizacao = $this->OrganizacoesModel->pegaDadosBasicosOrganizacao($codOrganizacao);

		session()->set('descricaoOrganizacao', $dadosOrganizacao->descricao);
		session()->set('siglaOrganizacao', $dadosOrganizacao->siglaOrganizacao);
		session()->set('logo', $dadosOrganizacao->logo);
		session()->set('cidade', $dadosOrganizacao->cidade);
		session()->set('endereço', $dadosOrganizacao->endereço);
		session()->set('telefone', $dadosOrganizacao->telefone);
		session()->set('uf', $dadosOrganizacao->siglaEstadoFederacao);
		session()->set('cep', $dadosOrganizacao->cep);
		session()->set('faleConosco', $dadosOrganizacao->faleConosco);
		session()->set('contatos', $dadosOrganizacao->contatos);
		session()->set('hero', $dadosOrganizacao->hero);
	}



	public function index()
	{

		if (session()->codPessoa == NULL) {
			helper('form');

			echo view('tema/cabecalho');
			// echo view('tema/menu_vertical');

			echo view('loginLocalizador');
			//echo view('tema/rodape');
		} else {

			if (empty(session()->minhasEspecialidades)) {
				session()->setFlashdata('mensagem_erro', 'Você não está cadastrado na especialidade que possui acesso a este recurso. Solicite a sua inclusão junto ao setor de TI.');

				exit();
			}

			helper('form');

			$data = [
				'codAtendimentoPrescricao' => 1,
			];

			echo view('tema/cabecalho');
			echo view('tema/menu_vertical');
			echo view('tema/menu_horizontal');

			echo view('localizador', $data);
		}
	}

	public function prescricao($codAtendimentoPrescricao = NULL)
	{

		if (session()->codPessoa == NULL) {
			helper('form');

			echo view('tema/cabecalho');
			// echo view('tema/menu_vertical');
			$dados = [
				'codAtendimentoPrescricao' => $codAtendimentoPrescricao,
			];

			echo view('loginLocalizador', $dados);
			exit();
		}


		if (empty(session()->minhasEspecialidades)) {

			echo view('tema/cabecalho');
			// echo view('tema/menu_vertical');
			$dados = [
				'codAtendimentoPrescricao' => $codAtendimentoPrescricao,
			];
			session()->setFlashdata('mensagem_erro', 'Você não tem acesso a este recurso');

			echo view('loginLocalizador', $dados);
			exit();
		}

		$atendimentos = $this->AtendimentosPrescricoesModel->atendimentoPorCodAtendimentoPrescricao($codAtendimentoPrescricao);

		$nrAtendimento = str_pad($atendimentos->codAtendimento, 8, "0", STR_PAD_LEFT) . '/' . (string) date('Y');
		$nrPrescricao = str_pad($atendimentos->codAtendimentoPrescricao, 8, "0", STR_PAD_LEFT) . '/' . (string) date('Y');

		$periodo = 'De ' . date('d/m/Y', strtotime($atendimentos->dataInicio)) . " à " . date('d/m/Y', strtotime($atendimentos->dataEncerramento));

		$dadosAtendimento = '
        <div class="row border">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        Atendimento Nº: ' . $nrAtendimento . ' 
                    </div>
                    <div class="col-md-3">
                        Prescrição Nº: ' . $nrPrescricao . ' 
                    </div>
                    <div class="col-md-6 class="text-right">
                        Período Prescrição: ' . $periodo . ' 
                    </div>
                </div>
                <div style="font-weight: bold;" class="row">
                    <div class="col-md-3">
                        Paciente: ' . $atendimentos->paciente . ' 
                    </div>
                    <div class="col-md-3">
                        Nº Plano: ' . $atendimentos->codPlano . ' 
                    </div>
                    <div class="col-md-6 class="text-right">
                        Prontuário: ' . $atendimentos->codProntuario . ' 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        IDADE: ' . $atendimentos->idade . ' 
                    </div>
                    <div class="col-md-3">
                        Situação: ' . $atendimentos->siglaTipoBeneficiario . ' 
                    </div>							
                    <div style="font-weight: bold;" class="col-md-3">
                        Local: ' . $atendimentos->abreviacaoDepartamento . ' (' . $atendimentos->descricaoLocalAtendimento . ')' . ' 
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-3">
                        PRESCRITO POR: ' . $atendimentos->especialista . ' 
                    </div>
				</div>
            </div>
        </div>
        ';

		$data = [
			'controller'        => 'atendimentos',
			'title'             => 'Processamento Prescricao',
			'codAtendimentoPrescricao' => $codAtendimentoPrescricao,
			'dadosAtendimento' => $dadosAtendimento,
		];
		echo view('tema/cabecalho');
		echo view('tema/menu_vertical');
		echo view('tema/menu_horizontal');
		return view('localizador', $data);
	}
}
