<?php

namespace App\Services\v1;

use App\Repositories\CckpiRepository;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use \JsonMachine\Items;
use mikehaertl\wkhtmlto\Pdf;

class CckpiService extends BaseService
{
    private CckpiRepository $repository;

    public function __construct() {
        $this->repository = new CckpiRepository();
    }

    public function upload($cckpi)
    {
        $content = Items::fromFile($cckpi->getPathName(), ['decoder' => new ExtJsonDecoder(true)]);
        foreach ($content as $item) {
            if (!isset($item['Year']) || !isset($item['Month'])) {
                return $this->errValidate('Parse error, make sure that Year and Month provided');
            }
            $item = $this->getCckpiFields($item);
            $tkpi = $this->repository->getByYearAndMonth($item['Year'], $item['Month'], $item['CompanyClientID']);
            if (is_null($tkpi)) {
                $this->repository->store($item);
            }
            else {
                $this->repository->update($tkpi, $item);
            }
        }
        return $this->ok();
    }

    public function delete(array $params)
    {
        $this->repository->deleteByParams($params);
        return $this->ok();
    }

    public function generatePdf($params)
    {
        $cckpiFirst = $this->repository->index($params);
        if ($cckpiFirst->isEmpty()) {
            return $this->errNotFound(__('kpi.not_found'));
        }

        $cckpis = $cckpiFirst;

        if ($params['Type'] == 'Monthly Comparation') {
            $cckpiSecond = $this->repository->index(['Year' => $params['SecondYear'], 'Month' => $params['SecondMonth']]);
            $cckpis = $cckpis->merge($cckpiSecond);
        }

        $orientation = 'portrait';
        $pageSize = 'A4';
        if ($params['Type'] == 'Yearly') {
            $orientation = 'landscape';
            $pageSize = 'A3';
        }

        $render = view('exports.cckpi', compact('cckpis', 'params'))->render();
        $pdf = new Pdf;
        $pdf->addPage($render);
        $pdf->setOptions([
            'javascript-delay' => 2500,
            'orientation' => $orientation,
            'page-size' => $pageSize,
        ]);
        return $pdf->send('name.pdf', false, array(
                'Content-Length' => false,
                'Access-Control-Allow-Origin' => '*',
            ));
    }

    protected function getCckpiFields(array $item)
    {
        return Arr::only($item, [
            'Year', 'Month', 'CompanyClientID', 'DaysOverall', 'CCDissued', 'Jobs',
            'LineItems', 'PreInspectionArranged', 'CleanItemsWithoutPreInspection_Quantity',
            'CleanItemsWithoutPreInspection_Percent',
            'CleanItemsWithoutPreInspection_Average',
            'CleanItemsWithPreInspection_Quantity',
            'CleanItemsWithPreInspection_Percent',
            'CleanItemsWithPreInspection_Average',
            'CleanItemsSubjectToCOC_Quantity',
            'CleanItemsSubjectToCOC_Percent',
            'CleanItemsSubjectToCOC_Average',
            'OtherNMT_Quantity', 'OtherNMT_Percent', 'OtherNMT_Average',
            'POIssues_Quantity', 'POIssues_Percent', 'POIssues_Average',
            'LackOfTDS_Quantity', 'LackOfTDS_Percent', 'LackOfTDS_Average',
            'DiscrepanciesGoodsVsDocuments_Quantity', 'DiscrepanciesGoodsVsDocuments_Percent', 'DiscrepanciesGoodsVsDocuments_Average',
            'RiskSystemProgramAstana1_Quantity', 'RiskSystemProgramAstana1_Percent', 'RiskSystemProgramAstana1_Average',
            'AverageCleanLineItems_Quantity', 'AverageCleanLineItems_Percent', 'AverageCleanLineItems_Average',
            'AverageCleanLineItemsWithPreInspection_Quantity', 'AverageCleanLineItemsWithPreInspection_Percent', 'AverageCleanLineItemsWithPreInspection_Average',
            'AverageCleanItemsSubjectToCOC_Quantity', 'AverageCleanItemsSubjectToCOC_Percent', 'AverageCleanItemsSubjectToCOC_Average',
            'AverageProblematicItems_Quantity', 'AverageProblematicItems_Percent', 'AverageProblematicItems_Average'
        ]);
    }
}
