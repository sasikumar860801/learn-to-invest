<!-- https://mir-s3-cdn-cf.behance.net/project_modules/hd/b6e0b072897469.5bf6e79950d23.gif -->

@foreach($data as $item)
<tr>
    <td class="stock-id" style="display: none">{{ $item->stock_id }}</td>
    <td class="serial-number">{{ $loop->index + 1 }}</td> <!-- Serial number -->

    <td class="stock-details">
        <span class="sticky">{{ $item->stock_name }}</span><br>
        <span class="stock-quantity">{{ $item->quantity }} Shares</span>
    </td>
    <!-- <td >{{ $item->buy_date }}</td> -->
    <td>{{ \Carbon\Carbon::parse($item->buy_date)->format('d M Y') }}</td>

    <td>
        @if(isset($data2[$item->stock_id]['one_day_change_value']))
            <span>{{ $data2[$item->stock_id]['one_day_change_value'] }}</span><br>
            <span>{{ $data2[$item->stock_id]['one_day_change_percentage'] }}%</span>
        @else
            N/A
        @endif
    </td>
    <td>
        @if(isset($data2[$item->stock_id]['latest_price']))
            @php
                $latestPrice = $data2[$item->stock_id]['latest_price'];
                $value1 = round($latestPrice * $item->quantity);
                $value2 = round($value1 - $item->total_price);
            @endphp
            {{ $item->total_price }}<br>
            <span style="color: {{ $value2 > 0 ? 'green' : ($value2 < 0 ? 'red' : 'black') }}">
                {{ $value1 }}
            </span>
            <input type="hidden" class="latestPrice" data-stock-id="{{ $item->stock_id }}" value="{{ $latestPrice }}">
        @else
            N/A
        @endif
    </td>
    <td>
        @php
        $percentage = round($value2 / $item->total_price * 100, 1);
        @endphp
        <span style="color: {{ $value2 > 0 ? 'green' : ($value2 < 0 ? 'red' : 'black') }}">
            {{ $value2 }}
        </span><br>
        <span style="color: {{ $percentage > 0 ? 'green' : ($value2 < 0 ? 'red' : 'black') }}">
            {{ $percentage }}
        </span>%
    </td>
   
    <td>
        <button type="button" class="btn btn-danger exit-button" data-toggle="modal" data-target="#myModal">Exit</button>
    </td>
</tr>
@endforeach
<tr>
    <td colspan="8">{{ $data->links() }}</td>
</tr>
