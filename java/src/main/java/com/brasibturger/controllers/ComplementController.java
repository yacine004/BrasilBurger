package com.brasibturger.controllers;

import java.util.List;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.brasibturger.dtos.ComplementDTO;
import com.brasibturger.services.ComplementService;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/complements")
@RequiredArgsConstructor
public class ComplementController {
    
    private final ComplementService complementService;
    
    @GetMapping
    public ResponseEntity<List<ComplementDTO>> getAllComplements() {
        return ResponseEntity.ok(complementService.getAllComplements());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<ComplementDTO>> getActiveComplements() {
        return ResponseEntity.ok(complementService.getActiveComplements());
    }
    
    @GetMapping("/type/{typeComplement}")
    public ResponseEntity<List<ComplementDTO>> getComplementsByType(@PathVariable String typeComplement) {
        return ResponseEntity.ok(complementService.getComplementsByType(typeComplement));
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<ComplementDTO> getComplementById(@PathVariable Long id) {
        return ResponseEntity.ok(complementService.getComplementById(id));
    }
    
    @PostMapping
    public ResponseEntity<ComplementDTO> createComplement(@Valid @RequestBody ComplementDTO complementDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(complementService.createComplement(complementDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<ComplementDTO> updateComplement(
            @PathVariable Long id,
            @Valid @RequestBody ComplementDTO complementDTO) {
        return ResponseEntity.ok(complementService.updateComplement(id, complementDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteComplement(@PathVariable Long id) {
        complementService.deleteComplement(id);
        return ResponseEntity.noContent().build();
    }
}
