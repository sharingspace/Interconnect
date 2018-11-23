<?php 

    function  generate_card($data) {
        $card = '
        <div class="col-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center"><strong>'.$data['title'].'</strong></h5>
                    <div class="row">
                    <div class="col-10 offset-2">
                        <div>
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="height: '.$data['Physical'].'%; background-color: green;" title="Physical '.$data['Physical'].'%" > 
                                 <span style="writing-mode: vertical-rl; transform: rotate(-180deg);">Physical '.$data['Physical'].'%</span>
                                </div>
                            </div>
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="height: '.$data['Emotion'].'%;" title="Emotion '.$data['Emotion'].'%">
                                    <span style="writing-mode: vertical-rl; transform: rotate(-180deg);">Emotion '.$data['Emotion'].'%</span>
                                </div>
                            </div>
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar  progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="height: '.$data['Intelect'].'%; background-color: red;"  title="Intellect '.$data['Intelect'].'%">
                                    <span style="writing-mode: vertical-rl; transform: rotate(-180deg);">Intellect '.$data['Intelect'].'%</span>
                                </div>
                            </div>
                            <div class="progress progress-bar-vertical">
                                <div class="progress-bar  progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="height: '.$data['Spirit'].'%; background-color: orange;"  title="Spirit '.$data['Spirit'].'%">
                                    <span style="writing-mode: vertical-rl; transform: rotate(-180deg);">Spirit '.$data['Spirit'].'%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6BAMAAAB6wkcOAAAAG1BMVEXMzMyWlpacnJyqqqrFxcWxsbGjo6O3t7e+vr6He3KoAAAACXBIWXMAAA7EAAAOxAGVKw4bAAADgUlEQVR4nO2bzW/bRhDFHyl+HbmSLPpI2k6jo5TWaI5kbSRXSoecycKAfaTUwupRcorUf3Znl7RJxwbiCBQCBO8HaJfkk/ZxdmeHOkgAIYQQQgghhBBCCCGEEEIIIYQQQgghhBBCCCGEEEIIIYQQQgj5OQje/VIBmVJqDLjJmxdlR2SVAsth3q/7SqkjYFe7l0oVL8lB7e4pNenV3FHvP8u4q983m7/hqn9WRy/J/miz2aRYRP+pXoN3JZj5DItKn2QRfD3Bz2Q3qm+lwmrcp7t3LNMeY7HVJ6tCJl/fx06ulkUru8da9uVWvKhPd1tisULMU30yl2ldzLTpRJY6beWBiXggq+KO+nT3YjN8Ce1VbiXSAjrLKm/Ukb3QQT0RQb9pJ6xinKzVDZDILWSxvjSPd+OObL9NJlszEc6wZ3MnyaF3VN5xz6Iy78iW0hvvIO53E8nmm6AcQ8HMs+CqYUdGNtl+UamRVL/mvjqDcy6rOurE7iRRR8aFTEQ5O0Ts8yaLJZ9ad5Tjr2RJxwO4e6qqDxzV5rwk/egrGVl4gJyfT5sDiepxv8uGb2reoyyT0v9+d4baRVcwqWSPtU4Kqil1jWwuror+a11dwfW49lGnzif57riVzW0l+QHqfHR/f7+VHfcxidtnnNyDN2ll2XH/riXden/G2brOhPoxrrbt813mITDp1si+tMfo//lu1cMHfw5/Q/vdZjHWxbaV8SU5TXGA7zaEEEIIIYQQQgghhBBCCCGEEEIIIYQQQgghhBBCnmI1r5/XXZkfsL/CPZQz/bN2DAE7hPlzDS6Sa+CDuoIVA8n3u4fOr693N+1ki9vG3XmTfpbmcplaN/DLPdzxB96dwk59OXBPZrCWoe4GJwvtvjyFV4nScY8KXDfug7xu3Jl1C2+1l7t79SEfVBn+wqfLc1hXpvt0qUOxRApinHfdw2kQN+52WjdObNnbuz2SRGbeTp04mC2r9ziTKK3UdGfItLtIuJYXzN+5avdbr2rcrYcmtPzieg93yToTUjwtYu2gB9Nd+LDuIe4G1ZPY7TWex4638T6x64/rAIubQgI2FrrrxG4v8cTdPcLzdcci389drzuW1TrHbXqhLXTXrjv86Km7OTFtJ+exV3HQo0jOY5dmKfxkqsfQXZvz8OMX3OvVuVAP+30/91fgVd9+z+FY/0hza/rt9xBCfhT/A2Hwit6kVCNWAAAAAElFTkSuQmCC" alt="img" class="mt-2 p-3">
                </div>
            </div>
            </div>
        </div>';
        return $card;
    }