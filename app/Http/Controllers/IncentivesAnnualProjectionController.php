<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIncentivesAnnualProjectionRequest;
use App\Http\Requests\UpdateIncentivesAnnualProjectionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\IncentivesAnnualProjectionRepository;
use Illuminate\Http\Request;
use App\Models\IncentivesAnnualProjection;
use App\Models\EmployeeIncentiveAnnualProjections;
use App\Models\IDGenerator;
use Flash;

class IncentivesAnnualProjectionController extends AppBaseController
{
    /** @var IncentivesAnnualProjectionRepository $incentivesAnnualProjectionRepository*/
    private $incentivesAnnualProjectionRepository;

    public function __construct(IncentivesAnnualProjectionRepository $incentivesAnnualProjectionRepo)
    {
        $this->middleware('auth');
        $this->incentivesAnnualProjectionRepository = $incentivesAnnualProjectionRepo;
    }

    /**
     * Display a listing of the IncentivesAnnualProjection.
     */
    public function index(Request $request)
    {
        $incentivesAnnualProjections = $this->incentivesAnnualProjectionRepository->paginate(10);

        return view('incentives_annual_projections.index')
            ->with('incentivesAnnualProjections', $incentivesAnnualProjections);
    }

    /**
     * Show the form for creating a new IncentivesAnnualProjection.
     */
    public function create()
    {
        return view('incentives_annual_projections.create');
    }

    /**
     * Store a newly created IncentivesAnnualProjection in storage.
     */
    public function store(CreateIncentivesAnnualProjectionRequest $request)
    {
        $input = $request->all();

        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->create($input);

        Flash::success('Incentives Annual Projection saved successfully.');

        return redirect(route('incentivesAnnualProjections.index'));
    }

    /**
     * Display the specified IncentivesAnnualProjection.
     */
    public function show($id)
    {
        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->find($id);

        if (empty($incentivesAnnualProjection)) {
            Flash::error('Incentives Annual Projection not found');

            return redirect(route('incentivesAnnualProjections.index'));
        }

        return view('incentives_annual_projections.show')->with('incentivesAnnualProjection', $incentivesAnnualProjection);
    }

    /**
     * Show the form for editing the specified IncentivesAnnualProjection.
     */
    public function edit($id)
    {
        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->find($id);

        if (empty($incentivesAnnualProjection)) {
            Flash::error('Incentives Annual Projection not found');

            return redirect(route('incentivesAnnualProjections.index'));
        }

        return view('incentives_annual_projections.edit')->with('incentivesAnnualProjection', $incentivesAnnualProjection);
    }

    /**
     * Update the specified IncentivesAnnualProjection in storage.
     */
    public function update($id, UpdateIncentivesAnnualProjectionRequest $request)
    {
        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->find($id);

        // if (empty($incentivesAnnualProjection)) {
        //     Flash::error('Incentives Annual Projection not found');

        //     return redirect(route('incentivesAnnualProjections.index'));
        // }

        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->update($request->all(), $id);

        // Flash::success('Incentives Annual Projection updated successfully.');

        // return redirect(route('incentivesAnnualProjections.index'));
        return response()->json($incentivesAnnualProjection);
    }

    /**
     * Remove the specified IncentivesAnnualProjection from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $incentivesAnnualProjection = $this->incentivesAnnualProjectionRepository->find($id);

        if (empty($incentivesAnnualProjection)) {
            Flash::error('Incentives Annual Projection not found');

            return redirect(route('incentivesAnnualProjections.index'));
        }

        $this->incentivesAnnualProjectionRepository->delete($id);

        Flash::success('Incentives Annual Projection deleted successfully.');

        return redirect(route('incentivesAnnualProjections.index'));
    }

    public function insertDatas(Request $request) {
        $data = $request['Data'];
        $year = $request['Year'];

        $dataArr = [];
        foreach($data as $item) {
            $incentive = IncentivesAnnualProjection::where('Incentive', $item['Incentive'])
                ->where('Year', $year)
                ->first();

            if ($incentive == null) {
                $incentive = new IncentivesAnnualProjection;
                $incentive->id = IDGenerator::generateIDandRandString();
                $incentive->Year = $year;
            }   
            
            $incentive->Incentive = $item['Incentive'];
            $incentive->Amount = $item['Amount'];
            $incentive->MaxUntaxableAmount = $item['MaxUntaxableThreshold'];
            $incentive->IsTaxable = $item['Taxable'];
            $incentive->save();

            array_push($dataArr, $incentive);
        }

        return response()->json($dataArr, 200);
    }

    public function getIncentivesPerYear(Request $request) {
        $year = $request['Year'];

        $data = IncentivesAnnualProjection::where('Year', $year)->orderBy('Incentive')->get();

        return response()->json($data, 200);
    }

    public function updateData(Request $request) {
        $id = $request['id'];
        $data = $request['Data'];
        $colToUpdate = $request['ColumnToUpdate'];
        $year = $request['Year'];

        $newId = IDGenerator::generateIDandRandString();

        if (isset($request['id']) || $id != null) {
            // UPDATE DATA
            $incentive = IncentivesAnnualProjection::find($id);

            if ($incentive != null) {            
                if ($colToUpdate == 'Amount') {
                    $incentive->Amount = $data;
                } elseif ($colToUpdate == 'MaxUntaxableThreshold') {
                    $incentive->MaxUntaxableAmount = $data;
                } elseif ($colToUpdate == 'IsTaxable') {
                    $incentive->IsTaxable = $data;
                } elseif ($colToUpdate == 'Incentive') {
                    $incentive->Incentive = $data;
                }

                $incentive->save();
            } else {
                // CREATE NEW
                $incentive = new IncentivesAnnualProjection;
                $incentive->id = $id;
                $incentive->Year = $year;

                if ($colToUpdate == 'Amount') {
                    $incentive->Amount = $data;
                } elseif ($colToUpdate == 'MaxUntaxableThreshold') {
                    $incentive->MaxUntaxableAmount = $data;
                } elseif ($colToUpdate == 'IsTaxable') {
                    $incentive->IsTaxable = $data;
                } elseif ($colToUpdate == 'Incentive') {
                    $incentive->Incentive = $data;
                }

                $incentive->save();
            }
        } else {
            // CREATE NEW
            $incentive = new IncentivesAnnualProjection;
            $incentive->id = $newId;

            if ($colToUpdate == 'Amount') {
                $incentive->Amount = $data;
            } elseif ($colToUpdate == 'MaxUntaxableThreshold') {
                $incentive->MaxUntaxableAmount = $data;
            } elseif ($colToUpdate == 'IsTaxable') {
                $incentive->IsTaxable = $data;
            } elseif ($colToUpdate == 'Incentive') {
                $incentive->Incentive = $data;
            }

            $incentive->save();
        }        

        return response()->json($incentive);
    }

    public function remove(Request $request) {
        $projection = IncentivesAnnualProjection::find($request['id']);

        // REMOVE Employe Projections as well
        EmployeeIncentiveAnnualProjections::where('Incentive', $projection->Incentive)
            ->where('Year', $projection->Year)
            ->delete();
        
        $projection->delete();

        return response()->json('ok', 200);
    }
}
