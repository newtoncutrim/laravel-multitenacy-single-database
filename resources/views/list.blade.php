<table>
    <th>id</th>
    <th>nome</th>

    @foreach ($data as $info)
    <tr>
        <td>{{$info['id']}}
        </td>
        <td>{{$info['author']}}
        </td>
    </tr>

    @endforeach



</table>

