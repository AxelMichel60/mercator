<?php

namespace App\Http\Controllers\Admin;

use App\Bay;
use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalRouterRequest;
use App\Http\Requests\StorePhysicalRouterRequest;
use App\Http\Requests\UpdatePhysicalRouterRequest;
use App\PhysicalRouter;
use App\Router;
use App\Site;
use App\Vlan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhysicalRouterController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_router_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouters = PhysicalRouter::all();

        return view('admin.physicalRouters.index', compact('physicalRouters'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalRouter::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.physicalRouters.create',
            compact('sites', 'buildings', 'bays', 'routers', 'vlans', 'type_list')
        );
    }

    public function store(StorePhysicalRouterRequest $request)
    {
        $physicalRouter = PhysicalRouter::create($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));
        $physicalRouter->routers()->sync($request->input('routers', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function edit(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $vlans = Vlan::all()->sortBy('name')->pluck('name', 'id');
        $routers = Router::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalRouter::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view(
            'admin.physicalRouters.edit',
            compact('sites', 'buildings', 'bays', 'vlans', 'physicalRouter', 'routers', 'type_list')
        );
    }

    public function update(UpdatePhysicalRouterRequest $request, PhysicalRouter $physicalRouter)
    {
        $physicalRouter->update($request->all());
        $physicalRouter->vlans()->sync($request->input('vlans', []));
        $physicalRouter->routers()->sync($request->input('routers', []));

        return redirect()->route('admin.physical-routers.index');
    }

    public function show(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->load('site', 'building', 'bay', 'vlans');

        return view('admin.physicalRouters.show', compact('physicalRouter'));
    }

    public function destroy(PhysicalRouter $physicalRouter)
    {
        abort_if(Gate::denies('physical_router_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalRouter->delete();

        return redirect()->route('admin.physical-routers.index');
    }

    public function massDestroy(MassDestroyPhysicalRouterRequest $request)
    {
        PhysicalRouter::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
