@extends('layouts.master')

@section('content')
<h2>リアルタイム通知</h2>
<h3>急騰急落通知</h3>
<p>
変化率を設定してください。</br>
約１分毎に監視を行い、設定した変化率以上の変動があった場合、メールで通知を行います。
</p>
<form method="POST" action="/Realtimes/auto">
  {{ csrf_field()}}
  <p>ユーザー名：{{ $users->first()->name }}</p>
  <input type="hidden" name="id" value={{ $users->first()->id }} >
  <!--
  <div class="form-group">
    @if($users->first()->autoupdate == true)
      <button type="submit" class="btn btn-primary">自動更新中</button>
    @else
      <button type="submit" class="btn btn-primary">自動更新停止中</button>
    @endif
  </div>
-->
</form>
<!--
<a href="/Realtimes/update">更新</a>
<a href="/Realtimes/create">追加</a>
-->
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>コード</th>
        <th>銘柄名</th>
        <!-- <th>現在値</th> -->
        <!-- <th>上限値</th> -->
        <!-- <th>下限値</th>  -->
        <th>騰落率</th>
        <th>騰落率(前回値)</th>
        <th>変化率</th>
      </tr>
    </thead>
    <tbody>
      @foreach($Realtimes as $realtime)
        <tr>
          <td>
            <form method="POST" action="/Realtimes/config_changerate">
              {{ csrf_field()}}
              <input type="hidden" name="code" value={{ $realtime->code }} >
              <div class="form-group">
                <button type="submit" class="btn btn-primary">設定</button>
              </div>
            </form>
          </td>
          <td>{{ $realtime->code }}</td>
          <td>{{ $realtime->name }}</td>
          <!--
          <td>{{ $realtime->value }}</td>
          <td>{{ $realtime->upperlimit }}</td>
          <td>{{ $realtime->lowerlimit }}</td>
        -->
          <td>{{ $realtime->changerate }}</td>
          <td>{{ $realtime->pre_changerate }}</td>
          <td>{{ $realtime->changerate_range }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection
